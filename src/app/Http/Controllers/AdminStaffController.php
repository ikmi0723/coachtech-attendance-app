<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminStaffController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }

        $staffs = User::where('role', 'user')
            ->orderBy('id')
            ->get();

        return view('admin.staff.list', [
            'staffs' => $staffs,
        ]);
    }
}
