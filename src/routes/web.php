<?php

use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminStaffAttendanceController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminStampCorrectionRequestController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\StampCorrectionRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'user.role'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])
        ->name('attendance.clock_in');

    // 休憩入
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])
        ->name('attendance.break_start');

    // 休憩戻
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])
        ->name('attendance.break_end');

    // 退勤
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])
        ->name('attendance.clock_out');

    // 勤怠一覧画面
    Route::get('/attendance/list', [AttendanceListController::class, 'index'])
        ->name('attendance.list');

    // 勤怠詳細画面
    Route::get('/attendance/detail/{id}', [AttendanceDetailController::class, 'show'])
        ->name('attendance.detail');

    // 修正申請送信
    Route::post('/attendance/detail/{id}', [AttendanceDetailController::class, 'update'])
        ->name('attendance.detail.update');

    // 一般ユーザーの申請一覧画面
    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])
        ->name('stamp_correction_request.list');

    // 一般ユーザーの申請詳細画面
    Route::get('/stamp_correction_request/detail/{id}', [StampCorrectionRequestController::class, 'show'])
        ->name('stamp_correction_request.detail');
});

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store']);

Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'index'])
    ->middleware('auth')
    ->name('admin.attendance.list');

// 管理者の勤怠詳細画面表示
Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'show'])
    ->middleware('auth')
    ->name('admin.attendance.detail');

// 管理者の勤怠更新
Route::post('/admin/attendance/{id}', [AdminAttendanceController::class, 'update'])
    ->middleware('auth')
    ->name('admin.attendance.update');

// 管理者のスタッフ一覧画面
Route::get('/admin/staff/list', [AdminStaffController::class, 'index'])
    ->middleware('auth')
    ->name('admin.staff.list');

// 管理者のスタッフ別月次勤怠一覧
Route::get('/admin/attendance/staff/{id}', [AdminStaffAttendanceController::class, 'index'])
    ->middleware('auth')
    ->name('admin.staff.attendance');

// 管理者の修正申請一覧画面
Route::get('/admin/stamp_correction_request/list', [AdminStampCorrectionRequestController::class, 'index'])
    ->middleware('auth')
    ->name('admin.stamp_correction_request.list');

// 管理者の申請詳細画面
Route::get('/admin/stamp_correction_request/detail/{id}', [AdminStampCorrectionRequestController::class, 'show'])
    ->middleware('auth')
    ->name('admin.stamp_correction_request.detail');

// 管理者の申請承認処理
Route::post('/admin/stamp_correction_request/approve/{id}', [AdminStampCorrectionRequestController::class, 'approve'])
    ->middleware('auth')
    ->name('admin.stamp_correction_request.approve');
