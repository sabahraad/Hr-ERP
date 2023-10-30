<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[App\Http\Controllers\FrontendController\AuthController::class, 'loginForm']);
Route::get('/login', [App\Http\Controllers\FrontendController\AuthController::class, 'loginForm'])->name('loginForm');
Route::get('/login-form', [App\Http\Controllers\FrontendController\AuthController::class, 'loginForm'])->name('loginForm');

Route::get('/registration-form', [App\Http\Controllers\FrontendController\AuthController::class, 'registrationForm'])->name('registrationForm');

Route::post('/registration', [App\Http\Controllers\FrontendController\AuthController::class, 'registration'])->name('registration');
Route::post('/login', [App\Http\Controllers\FrontendController\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\FrontendController\AuthController::class, 'logout'])->name('logout');
Route::get('/test', [App\Http\Controllers\FrontendController\AuthController::class, 'test'])->name('test');

Route::group(['middleware' => ['check_access_token' ,'prevent-back-history']], function () {
    Route::get('/company', [App\Http\Controllers\FrontendController\companyController::class, 'company'])->name('company');
    Route::get('/department',[App\Http\Controllers\FrontendController\departmentController::class, 'department'])->name('department');
    Route::get('/holidays', [App\Http\Controllers\FrontendController\HolidaysController::class, 'holidays'])->name('holidays');
    Route::get('/designation', [App\Http\Controllers\FrontendController\designationController::class, 'designation'])->name('designation');
    Route::get('/leave-list', [App\Http\Controllers\FrontendController\leaveController::class, 'leaveList'])->name('leaveList');
    Route::get('/office-location', [App\Http\Controllers\FrontendController\officeLocationController::class, 'officeLocation'])->name('officeLocation');
    Route::get('/ip-list', [App\Http\Controllers\FrontendController\ipController::class, 'ipList'])->name('ipList');
    Route::get('/weekend-list', [App\Http\Controllers\FrontendController\weekendController::class, 'weekendlist'])->name('weekendlist');
    Route::get('/employee', [App\Http\Controllers\FrontendController\employeeController::class, 'employee'])->name('employee');
    Route::get('/attendance-type', [App\Http\Controllers\FrontendController\attendanceController::class, 'attendanceType'])->name('attendanceType');
    Route::get('/attendance-setting', [App\Http\Controllers\FrontendController\attendanceController::class, 'attendanceSetting'])->name('attendanceSetting');
    Route::get('/attendance-list', [App\Http\Controllers\FrontendController\attendanceController::class, 'attendanceList'])->name('attendanceList');

});
