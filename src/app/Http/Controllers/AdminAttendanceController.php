<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAttendanceUpdateRequest;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $targetDate = $request->query('date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('date'))
            : Carbon::today();

        $users = User::where('role', 'user')
            ->orderBy('id')
            ->get();

        $attendances = Attendance::with('breakTimes')
            ->whereIn('user_id', $users->pluck('id'))
            ->whereDate('work_date', $targetDate->toDateString())
            ->get()
            ->keyBy('user_id');

        $staffAttendances = $users->map(function ($user) use ($attendances, $targetDate) {
            $attendance = $attendances->get($user->id);

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

            return [
                'name' => $user->name,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_time' => $breakTimeFormatted,
                'work_time' => $workTimeFormatted,
                'attendance_id' => $attendance?->id ?? 0,
                'user_id' => $user->id,
                'date_string' => $targetDate->toDateString(),
            ];
        });

        return view('admin.attendance.list', [
            'targetDate' => $targetDate,
            'previousDate' => $targetDate->copy()->subDay()->format('Y-m-d'),
            'nextDate' => $targetDate->copy()->addDay()->format('Y-m-d'),
            'staffAttendances' => $staffAttendances,
        ]);
    }

    // 管理者の勤怠詳細表示
    public function show(Request $request, int $id): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        if ($id === 0) {
            $user = User::where('id', $request->query('user_id'))
                ->where('role', 'user')
                ->firstOrFail();

            $targetDate = $request->query('date')
                ? Carbon::parse($request->query('date'))
                : Carbon::today();

            return view('admin.attendance.detail', [
                'attendance' => null,
                'userName' => $user->name,
                'displayDate' => $targetDate->locale('ja')->translatedFormat('Y年n月j日'),
                'clockIn' => '',
                'clockOut' => '',
                'breakInputs' => [
                    [
                        'break_start_at' => '',
                        'break_end_at' => '',
                    ],
                ],
                'isFutureDate' => $targetDate->isFuture(),
                'detailDate' => $targetDate->toDateString(),
                'detailUserId' => $user->id,
            ]);
        }

        $attendance = Attendance::with(['breakTimes', 'user'])
            ->where('id', $id)
            ->firstOrFail();

        $workDate = Carbon::parse($attendance->work_date);
        $isFutureDate = $workDate->isFuture();

        $breakInputs = $attendance->breakTimes
            ->map(function ($breakTime) {
                return [
                    'break_start_at' => $breakTime->break_start_at
                        ? Carbon::parse($breakTime->break_start_at)->format('H:i')
                        : '',
                    'break_end_at' => $breakTime->break_end_at
                        ? Carbon::parse($breakTime->break_end_at)->format('H:i')
                        : '',
                ];
            })
            ->values()
            ->toArray();

        $breakInputs[] = [
            'break_start_at' => '',
            'break_end_at' => '',
        ];

        return view('admin.attendance.detail', [
            'attendance' => $attendance,
            'userName' => $attendance->user->name,
            'displayDate' => $workDate->locale('ja')->translatedFormat('Y年n月j日'),
            'clockIn' => $attendance->clock_in_at
                ? Carbon::parse($attendance->clock_in_at)->format('H:i')
                : '',
            'clockOut' => $attendance->clock_out_at
                ? Carbon::parse($attendance->clock_out_at)->format('H:i')
                : '',
            'breakInputs' => $breakInputs,
            'isFutureDate' => $isFutureDate,
            'detailDate' => $workDate->toDateString(),
            'detailUserId' => $attendance->user_id,
        ]);
    }

    // 管理者の勤怠更新
    public function update(AdminAttendanceUpdateRequest $request, int $id): RedirectResponse
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        if ($id === 0) {
            $workDate = Carbon::parse($request->query('date'));
            $userId = (int) $request->query('user_id');

            if ($workDate->isFuture()) {
                return back()->withErrors([
                    'attendance' => '未来の日付は修正できません',
                ])->withInput();
            }

            $attendance = Attendance::create([
                'user_id' => $userId,
                'work_date' => $workDate->toDateString(),
                'clock_in_at' => Carbon::parse($workDate->toDateString() . ' ' . $request->input('clock_in')),
                'clock_out_at' => Carbon::parse($workDate->toDateString() . ' ' . $request->input('clock_out')),
                'note' => $request->input('note'),
            ]);

            foreach ($request->input('breaks', []) as $break) {
                $breakStart = $break['break_start_at'] ?? null;
                $breakEnd = $break['break_end_at'] ?? null;

                if (!$breakStart && !$breakEnd) {
                    continue;
                }

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start_at' => Carbon::parse($workDate->toDateString() . ' ' . $breakStart),
                    'break_end_at' => Carbon::parse($workDate->toDateString() . ' ' . $breakEnd),
                ]);
            }

            return redirect()
                ->route('admin.attendance.detail', ['id' => $attendance->id])
                ->with('message', '勤怠を更新しました');
        }

        $attendance = Attendance::with('breakTimes')
            ->where('id', $id)
            ->firstOrFail();

        $workDate = Carbon::parse($attendance->work_date);

        if ($workDate->isFuture()) {
            return back()->withErrors([
                'attendance' => '未来の日付は修正できません',
            ])->withInput();
        }

        $attendance->update([
            'clock_in_at' => Carbon::parse($workDate->toDateString() . ' ' . $request->input('clock_in')),
            'clock_out_at' => Carbon::parse($workDate->toDateString() . ' ' . $request->input('clock_out')),
            'note' => $request->input('note'),
        ]);

        $attendance->breakTimes()->delete();

        foreach ($request->input('breaks', []) as $break) {
            $breakStart = $break['break_start_at'] ?? null;
            $breakEnd = $break['break_end_at'] ?? null;

            if (!$breakStart && !$breakEnd) {
                continue;
            }

            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start_at' => Carbon::parse($workDate->toDateString() . ' ' . $breakStart),
                'break_end_at' => Carbon::parse($workDate->toDateString() . ' ' . $breakEnd),
            ]);
        }

        return redirect()
            ->route('admin.attendance.detail', ['id' => $attendance->id])
            ->with('message', '勤怠を更新しました');
    }
}
