<?php

use App\Http\Middleware\RoleCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetDefaultJsonResponse;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(SetDefaultJsonResponse::class)->group(function () {
    
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/forget-password', [App\Http\Controllers\AuthController::class, 'forgetPassword']);

    Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::get('/unauthorized',[App\Http\Controllers\Controller::class, 'unauthorized'])->name('unauthorized');
    Route::get('/user-profile', [App\Http\Controllers\AuthController::class, 'userProfile']);   

    Route::post('/attendance', [App\Http\Controllers\attendanceController::class, 'createAttendance'])->name('attendance');
    Route::get('/attendance-list', [App\Http\Controllers\attendanceController::class, 'showattendance'])->name('showattendance');
    
    Route::post('/leave-applications', [App\Http\Controllers\leaveController::class, 'createLeaveApplications'])->name('createLeaveApplications');
    Route::get('/currentDateStatus', [App\Http\Controllers\attendanceController::class, 'currentDateStatus'])->name('currentDateStatus');

});

Route::middleware([RoleCheck::class,SetDefaultJsonResponse::class])->group(function () {

Route::get('/company-list', [App\Http\Controllers\companyController::class, 'showCompany'])->name('showCompany');
Route::post('/edit/company', [App\Http\Controllers\companyController::class, 'editCompany'])->name('editCompany');
Route::delete('/delete/company', [App\Http\Controllers\companyController::class, 'deleteCompany'])->name('deleteCompany');

Route::post('/add-department', [App\Http\Controllers\departmentController::class, 'addDepartment'])->name('addDepartment');
Route::get('/department-list', [App\Http\Controllers\departmentController::class, 'showDepartment'])->name('showDepartment');
Route::post('/edit/department/{id}', [App\Http\Controllers\departmentController::class, 'editDepartment'])->name('editDepartment');
Route::delete('/delete/department/{id}', [App\Http\Controllers\departmentController::class, 'deleteDepartment'])->name('deleteDepartment');

Route::post('/add-designations', [App\Http\Controllers\designationsController::class, 'addDesignations'])->name('addDesignations');
Route::get('/designations-list/{id}', [App\Http\Controllers\designationsController::class, 'showDesignations'])->name('showDesignations');
Route::post('/edit/designations/{id}', [App\Http\Controllers\designationsController::class, 'editDesignations'])->name('editDesignations');
Route::delete('/delete/designations/{id}', [App\Http\Controllers\designationsController::class, 'deleteDesignations'])->name('deleteDesignations');

Route::post('/add-IP', [App\Http\Controllers\IpController::class, 'addIP'])->name('addIP');
Route::get('/IP-list', [App\Http\Controllers\IpController::class, 'showIP'])->name('showIP');
Route::delete('/delete/IP', [App\Http\Controllers\IpController::class, 'deleteIP'])->name('deleteIP');

Route::post('/edit/attendance/{id}', [App\Http\Controllers\attendanceController::class, 'updateattendance'])->name('updateattendance');

Route::post('add-leave-setting', [App\Http\Controllers\leaveController::class, 'addleavesetting'])->name('addleavesetting');
Route::get('leave-setting-list', [App\Http\Controllers\leaveController::class, 'leavesettingList'])->name('leavesettingList');
Route::post('/edit/leave-setting/{id}', [App\Http\Controllers\leaveController::class, 'updateleavesetting'])->name('updateleavesetting');
Route::delete('/delete/leave-setting/{id}', [App\Http\Controllers\leaveController::class, 'deleteleavesetting'])->name('deleteleavesetting');

Route::post('add-employee', [App\Http\Controllers\employeeController::class, 'addEmployee'])->name('addEmployee');
Route::get('employee-list', [App\Http\Controllers\employeeController::class, 'employeeList'])->name('employeeList');
Route::post('/edit/employee/{id}', [App\Http\Controllers\employeeController::class, 'updateEmployee'])->name('updateEmployee');
Route::delete('/delete/employee/{id}', [App\Http\Controllers\employeeController::class, 'deleteEmployee'])->name('deleteEmployee');

Route::post('add-office-location', [App\Http\Controllers\officeLocationController::class, 'createOfficeLocation'])->name('createOfficeLocation');
Route::get('office-location-list', [App\Http\Controllers\officeLocationController::class, 'OfficeLocationList'])->name('OfficeLocationList');
Route::post('/edit/office-location/{id}', [App\Http\Controllers\officeLocationController::class, 'updateOfficeLocation'])->name('updateOfficeLocation');
Route::delete('/delete/office-location/{id}', [App\Http\Controllers\officeLocationController::class, 'deleteOfficeLocation'])->name('deleteOfficeLocation');

Route::post('add-weekend', [App\Http\Controllers\weekendController::class, 'createWeekend'])->name('createWeekend');
Route::get('weekend-list', [App\Http\Controllers\weekendController::class, 'WeekendList'])->name('WeekendList');
Route::delete('/delete/weekend', [App\Http\Controllers\weekendController::class, 'deleteWeekend'])->name('deleteWeekend');

Route::post('add-holiday', [App\Http\Controllers\holidayController::class, 'createHoliday'])->name('createHoliday');
Route::get('holiday-list', [App\Http\Controllers\holidayController::class, 'HolidayList'])->name('HolidayList');
Route::post('/edit/holiday/{id}', [App\Http\Controllers\holidayController::class, 'updateHoliday'])->name('updateHoliday');
Route::delete('/delete/holiday/{id}', [App\Http\Controllers\holidayController::class, 'deleteHoliday'])->name('deleteHoliday');

Route::post('add-attendance-setting', [App\Http\Controllers\attendanceSettingsController::class, 'createAttendanceSetting'])->name('createAttendanceSetting');
Route::get('attendance-setting-list', [App\Http\Controllers\attendanceSettingsController::class, 'AttendanceSettingList'])->name('AttendanceSettingList');
Route::delete('/delete/attendance-setting', [App\Http\Controllers\attendanceSettingsController::class, 'deleteAttendanceSetting'])->name('deleteAttendanceSetting');

Route::post('add-attendance-type', [App\Http\Controllers\attendanceTypeController::class, 'createAttendanceType'])->name('createAttendanceType');
Route::get('attendance-type-details', [App\Http\Controllers\attendanceTypeController::class, 'AttendanceTypeList'])->name('AttendanceTypeList');
Route::delete('/delete/attendance-type', [App\Http\Controllers\attendanceTypeController::class, 'deleteAttendanceType'])->name('deleteAttendanceType');

Route::delete('/delete/attendance/{id}', [App\Http\Controllers\attendanceController::class, 'deleteattendance'])->name('deleteattendance');
Route::post('/update-reason', [App\Http\Controllers\attendanceController::class, 'updateReason'])->name('updateReason');

});
