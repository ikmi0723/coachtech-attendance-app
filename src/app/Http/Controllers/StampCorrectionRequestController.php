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
                    'work_date' => $correctionRequest->attendance
                        ? Carbon::parse($correctionRequest->attendance->work_date)->format('Y/m/d')
                        : '',
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
}
