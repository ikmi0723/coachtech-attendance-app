<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StampCorrectionRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $requests = AttendanceCorrectionRequest::with('attendance')
            ->where('user_id', $request->user()->id)
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($correctionRequest) {
                return [
                    'id' => $correctionRequest->id,
                    'request_date' => Carbon::parse($correctionRequest->created_at)->format('Y/m/d'),
                    'work_date' => $correctionRequest->requested_work_date
                        ? Carbon::parse($correctionRequest->requested_work_date)->format('Y/m/d')
                        : ($correctionRequest->attendance
                            ? Carbon::parse($correctionRequest->attendance->work_date)->format('Y/m/d')
                            : ''),
                    'note' => $correctionRequest->requested_note,
                    'status' => $correctionRequest->status === 'pending' ? '承認待ち' : '承認済み',
                    'attendance_id' => $correctionRequest->attendance_id,
                ];
            });

        return view('stamp_correction_request.list', [
            'status' => $status,
            'requests' => $requests,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $correctionRequest = AttendanceCorrectionRequest::with(['breakTimes', 'user'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
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

        return view('stamp_correction_request.detail', [
            'correctionRequest' => $correctionRequest,
            'displayDate' => $workDate->locale('ja')->translatedFormat('Y年n月j日'),
            'clockIn' => $correctionRequest->requested_clock_in_at
                ? Carbon::parse($correctionRequest->requested_clock_in_at)->format('H:i')
                : '',
            'clockOut' => $correctionRequest->requested_clock_out_at
                ? Carbon::parse($correctionRequest->requested_clock_out_at)->format('H:i')
                : '',
            'breakInputs' => $breakInputs,
            'statusLabel' => $correctionRequest->status === 'pending' ? '承認待ち' : '承認済み',
        ]);
    }
}
