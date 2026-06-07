<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrectionRequest;
use Carbon\Carbon;
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
}
