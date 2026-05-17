<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\AttendanceDetailController;
use App\Http\Controllers\StampCorrectionRequestController;
use App\Http\Controllers\AdminAttendanceListController;

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

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

Route::get('/admin/login', [AuthController::class, 'adminloginForm'])->name('admin.login');

Route::middleware('auth')->group(function()
{
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/list', [AttendanceListController::class, 'index'])->name('attendance.list');
    Route::get('/attendance/detail/{id}', [AttendanceDetailController::class, 'index'])->name('attendance.detail');
    Route::patch('/attendance/detail/{id}', [AttendanceDetailController::class, 'update'])->name('attendance.update');
    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('stamp_correction_request.list');
    
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/attendance/list', [AdminAttendanceListController::class, 'index']);

});
