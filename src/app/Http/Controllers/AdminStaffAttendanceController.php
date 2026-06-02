<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminStaffAttendanceController extends Controller
{
    public function index(Request $request, int $id): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $staff = User::where('id', $id)
            ->where('role', 'user')
            ->firstOrFail();

        $month = $request->query('month');
        $currentMonth = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : Carbon::today()->startOfMonth();

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $staff->id)
            ->whereBetween('work_date', [
                $startOfMonth->toDateString(),
                $endOfMonth->toDateString(),
            ])
            ->get()
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->work_date)->toDateString();
            });

        $days = [];
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        foreach ($period as $date) {
            $attendance = $attendances->get($date->toDateString());

            $clockIn = $attendance?->clock_in_at
                ? Carbon::parse($attendance->clock_in_at)->format('H:i')
                : '';

            $clockOut = $attendance?->clock_out_at
                ? Carbon::parse($attendance->clock_out_at)->format('H:i')
                : '';

            $breakMinutes = 0;

            if ($attendance) {
                foreach ($attendance->breakTimes as $breakTime) {
                    if ($breakTime->break_start_at && $breakTime->break_end_at) {
                        $breakMinutes += Carbon::parse($breakTime->break_start_at)
                            ->diffInMinutes(Carbon::parse($breakTime->break_end_at));
                    }
                }
            }

            $breakTimeFormatted = $breakMinutes > 0
                ? sprintf('%d:%02d', floor($breakMinutes / 60), $breakMinutes % 60)
                : '';

            $workTimeFormatted = '';

            if ($attendance && $attendance->clock_in_at && $attendance->clock_out_at) {
                $workMinutes = Carbon::parse($attendance->clock_in_at)
                    ->diffInMinutes(Carbon::parse($attendance->clock_out_at)) - $breakMinutes;

                $workTimeFormatted = sprintf('%d:%02d', floor($workMinutes / 60), $workMinutes % 60);
            }

            $days[] = [
                'date' => $date->copy(),
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_time' => $breakTimeFormatted,
                'work_time' => $workTimeFormatted,
                'attendance_id' => $attendance?->id ?? 0,
                'date_string' => $date->toDateString(),
            ];
        }

        return view('admin.attendance.staff', [
            'staff' => $staff,
            'currentMonth' => $currentMonth,
            'previousMonth' => $currentMonth->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $currentMonth->copy()->addMonth()->format('Y-m'),
            'days' => $days,
        ]);
    }
}
