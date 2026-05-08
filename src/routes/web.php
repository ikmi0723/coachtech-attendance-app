<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttendanceController;

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

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store']);

Route::get('/admin/attendance/list', function () {
    return 'admin attendance list page';
})->middleware('auth');
