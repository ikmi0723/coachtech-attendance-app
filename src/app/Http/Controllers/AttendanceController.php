<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        $status = 'outside';

        if ($attendance) {
            $isOnBreak = $attendance->breakTimes()
                ->whereNull('break_end_at')
                ->exists();

            if (!is_null($attendance->clock_out_at)) {
                $status = 'finished';
            } elseif ($isOnBreak) {
                $status = 'break';
            } elseif (!is_null($attendance->clock_in_at)) {
                $status = 'working';
            }
        }

        $statusLabels = [
            'outside' => '勤務外',
            'working' => '出勤中',
            'break' => '休憩中',
            'finished' => '退勤済',
        ];

        $now = Carbon::now();

        return view('attendance.index', [
            'status' => $status,
            'statusLabel' => $statusLabels[$status],
            'nowIso' => $now->toIso8601String(),
        ]);
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        if ($existingAttendance) {
            return redirect('/attendance');
        }

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->toDateString(),
            'clock_in_at' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    // 休憩入
    public function breakStart(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        if (!$attendance || is_null($attendance->clock_in_at) || !is_null($attendance->clock_out_at)) {
            return redirect('/attendance');
        }

        $isOnBreak = $attendance->breakTimes()
            ->whereNull('break_end_at')
            ->exists();

        if ($isOnBreak) {
            return redirect('/attendance');
        }

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start_at' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    // 休憩戻
    public function breakEnd(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        if (!$attendance || is_null($attendance->clock_in_at) || !is_null($attendance->clock_out_at)) {
            return redirect('/attendance');
        }

        $breakTime = $attendance->breakTimes()
            ->whereNull('break_end_at')
            ->latest('id')
            ->first();

        if (!$breakTime) {
            return redirect('/attendance');
        }

        $breakTime->update([
            'break_end_at' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }

    // 退勤
    public function clockOut(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::with('breakTimes')
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        if (!$attendance || is_null($attendance->clock_in_at) || !is_null($attendance->clock_out_at)) {
            return redirect('/attendance');
        }

        $isOnBreak = $attendance->breakTimes()
            ->whereNull('break_end_at')
            ->exists();

        if ($isOnBreak) {
            return redirect('/attendance');
        }

        $attendance->update([
            'clock_out_at' => Carbon::now(),
        ]);

        return redirect('/attendance');
    }
}
