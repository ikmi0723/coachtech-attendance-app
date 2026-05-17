<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionUpdateRequest;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionBreakTime;
use App\Models\AttendanceCorrectionRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceDetailController extends Controller
{
    public function show(Request $request, int $id): View
    {
        $attendance = Attendance::with('breakTimes')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
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

        $hasPendingRequest = AttendanceCorrectionRequest::where('attendance_id', $attendance->id)
            ->where('status', 'pending')
            ->exists();

        return view('attendance.detail', [
            'attendance' => $attendance,
            'displayDate' => $workDate->locale('ja')->translatedFormat('Y年n月j日'),
            'clockIn' => $attendance->clock_in_at
                ? Carbon::parse($attendance->clock_in_at)->format('H:i')
                : '',
            'clockOut' => $attendance->clock_out_at
                ? Carbon::parse($attendance->clock_out_at)->format('H:i')
                : '',
            'breakInputs' => $breakInputs,
            'isFutureDate' => $isFutureDate,
            'hasPendingRequest' => $hasPendingRequest,
        ]);
    }

    // 修正申請保存
    public function update(AttendanceCorrectionUpdateRequest $request, int $id): RedirectResponse
    {
        $attendance = Attendance::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $hasPendingRequest = AttendanceCorrectionRequest::where('attendance_id', $attendance->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return back()->withErrors([
                'request' => '承認待ちのため修正はできません',
            ])->withInput();
        }

        $workDate = Carbon::parse($attendance->work_date)->toDateString();

        $correctionRequest = AttendanceCorrectionRequest::create([
            'attendance_id' => $attendance->id,
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'requested_clock_in_at' => Carbon::parse($workDate . ' ' . $request->input('clock_in')),
            'requested_clock_out_at' => Carbon::parse($workDate . ' ' . $request->input('clock_out')),
            'requested_note' => $request->input('note'),
        ]);

        $breaks = $request->input('breaks', []);

        foreach ($breaks as $break) {
            $breakStart = $break['break_start_at'] ?? null;
            $breakEnd = $break['break_end_at'] ?? null;

            if (!$breakStart && !$breakEnd) {
                continue;
            }

            AttendanceCorrectionBreakTime::create([
                'attendance_correction_request_id' => $correctionRequest->id,
                'break_start_at' => Carbon::parse($workDate . ' ' . $breakStart),
                'break_end_at' => Carbon::parse($workDate . ' ' . $breakEnd),
            ]);
        }

        return redirect()
            ->route('attendance.detail', ['id' => $attendance->id])
            ->with('message', '修正申請を送信しました');
    }
}
