<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminStampCorrectionRequestController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $status = $request->query('status', 'pending');

        $requests = AttendanceCorrectionRequest::with(['attendance', 'user'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($correctionRequest) {
                return [
                    'id' => $correctionRequest->id,
                    'user_name' => $correctionRequest->user?->name ?? '',
                    'request_date' => Carbon::parse($correctionRequest->created_at)->format('Y/m/d'),
                    'work_date' => $correctionRequest->requested_work_date
                        ? Carbon::parse($correctionRequest->requested_work_date)->format('Y/m/d')
                        : ($correctionRequest->attendance
                            ? Carbon::parse($correctionRequest->attendance->work_date)->format('Y/m/d')
                            : ''),
                    'note' => $correctionRequest->requested_note,
                    'status' => $correctionRequest->status === 'pending' ? '承認待ち' : '承認済み',
                ];
            });

        return view('admin.stamp_correction_request.list', [
            'status' => $status,
            'requests' => $requests,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $correctionRequest = AttendanceCorrectionRequest::with(['attendance', 'user', 'breakTimes'])
            ->where('id', $id)
            ->firstOrFail();

        $workDate = $correctionRequest->requested_work_date
            ? Carbon::parse($correctionRequest->requested_work_date)
            : Carbon::parse($correctionRequest->attendance->work_date);

        $breakInputs = $correctionRequest->breakTimes
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

        return view('admin.stamp_correction_request.detail', [
            'correctionRequest' => $correctionRequest,
            'userName' => $correctionRequest->user?->name ?? '',
            'displayDate' => $workDate->locale('ja')->translatedFormat('Y年n月j日'),
            'clockIn' => $correctionRequest->requested_clock_in_at
                ? Carbon::parse($correctionRequest->requested_clock_in_at)->format('H:i')
                : '',
            'clockOut' => $correctionRequest->requested_clock_out_at
                ? Carbon::parse($correctionRequest->requested_clock_out_at)->format('H:i')
                : '',
            'breakInputs' => $breakInputs,
            'isApproved' => $correctionRequest->status === 'approved',
        ]);
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $correctionRequest = AttendanceCorrectionRequest::with(['attendance', 'user', 'breakTimes'])
            ->where('id', $id)
            ->firstOrFail();

        if ($correctionRequest->status === 'approved') {
            return redirect()
                ->route('admin.stamp_correction_request.detail', ['id' => $correctionRequest->id])
                ->with('message', 'この申請はすでに承認済みです');
        }

        $workDate = $correctionRequest->requested_work_date
            ? Carbon::parse($correctionRequest->requested_work_date)->toDateString()
            : Carbon::parse($correctionRequest->attendance->work_date)->toDateString();

        if ($correctionRequest->attendance_id) {
            $attendance = $correctionRequest->attendance;

            $attendance->update([
                'clock_in_at' => $correctionRequest->requested_clock_in_at,
                'clock_out_at' => $correctionRequest->requested_clock_out_at,
                'note' => $correctionRequest->requested_note,
            ]);

            $attendance->breakTimes()->delete();
        } else {
            $attendance = Attendance::create([
                'user_id' => $correctionRequest->user_id,
                'work_date' => $workDate,
                'clock_in_at' => $correctionRequest->requested_clock_in_at,
                'clock_out_at' => $correctionRequest->requested_clock_out_at,
                'note' => $correctionRequest->requested_note,
            ]);
        }

        foreach ($correctionRequest->breakTimes as $breakTime) {
            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start_at' => $breakTime->break_start_at,
                'break_end_at' => $breakTime->break_end_at,
            ]);
        }

        $correctionRequest->update([
            'attendance_id' => $attendance->id,
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.stamp_correction_request.detail', ['id' => $correctionRequest->id])
            ->with('message', '申請を承認しました');
    }
}
