<?php

use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminStaffAttendanceController;
use App\Http\Controllers\AdminStaffController;
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

Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('auth');

Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])
    ->middleware('auth')
    ->name('attendance.clock_in');

// 休憩入
Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])
    ->middleware('auth')
    ->name('attendance.break_start');

// 休憩戻
Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])
    ->middleware('auth')
    ->name('attendance.break_end');

// 退勤
Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])
    ->middleware('auth')
    ->name('attendance.clock_out');

// 勤怠一覧画面
Route::get('/attendance/list', [AttendanceListController::class, 'index'])
    ->middleware('auth')
    ->name('attendance.list');

// 勤怠詳細画面
Route::get('/attendance/detail/{id}', [AttendanceDetailController::class, 'show'])
    ->middleware('auth')
    ->name('attendance.detail');

// 修正申請送信
Route::post('/attendance/detail/{id}', [AttendanceDetailController::class, 'update'])
    ->middleware('auth')
    ->name('attendance.detail.update');

// 一般ユーザーの申請一覧画面
Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])
    ->middleware('auth')
    ->name('stamp_correction_request.list');

// 一般ユーザーの申請詳細画面
Route::get('/stamp_correction_request/detail/{id}', [StampCorrectionRequestController::class, 'show'])
    ->middleware('auth')
    ->name('stamp_correction_request.detail');

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
