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
Route::get('/', [App\Http\Controllers\frontendController\timeWiseController::class, 'timeWise']);
Route::get('/login', [App\Http\Controllers\frontendController\AuthController::class, 'loginForm'])->name('loginForm');
Route::get('/login-form', [App\Http\Controllers\frontendController\AuthController::class, 'loginForm'])->name('loginForm');

Route::get('/registration-form', [App\Http\Controllers\frontendController\AuthController::class, 'registrationForm'])->name('registrationForm');

Route::post('/registration', [App\Http\Controllers\frontendController\AuthController::class, 'registration'])->name('registration');
Route::post('/login', [App\Http\Controllers\frontendController\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\frontendController\AuthController::class, 'logout'])->name('logout');
Route::get('/test', [App\Http\Controllers\frontendController\AuthController::class, 'test'])->name('test');

Route::group(['middleware' => ['check_access_token' ,'prevent-back-history']], function () {
    Route::get('/company', [App\Http\Controllers\frontendController\companyController::class, 'company'])->name('company');
    Route::get('/department',[App\Http\Controllers\frontendController\departmentController::class, 'department'])->name('department');
    Route::get('/holidays', [App\Http\Controllers\frontendController\HolidaysController::class, 'holidays'])->name('holidays');
    Route::get('/designation', [App\Http\Controllers\frontendController\designationController::class, 'designation'])->name('designation');
    Route::get('/leave-list', [App\Http\Controllers\frontendController\leaveController::class, 'leaveList'])->name('leaveList');
    Route::get('/office-location', [App\Http\Controllers\frontendController\officeLocationController::class, 'officeLocation'])->name('officeLocation');
    Route::get('/ip-list', [App\Http\Controllers\frontendController\ipController::class, 'ipList'])->name('ipList');
    Route::get('/weekend-list', [App\Http\Controllers\frontendController\weekendController::class, 'weekendlist'])->name('weekendlist');
    Route::get('/employee', [App\Http\Controllers\frontendController\employeeController::class, 'employee'])->name('employee');
    Route::get('/attendance-type', [App\Http\Controllers\frontendController\attendanceController::class, 'attendanceType'])->name('attendanceType');
    Route::get('/attendance-setting', [App\Http\Controllers\frontendController\attendanceController::class, 'attendanceSetting'])->name('attendanceSetting');
    Route::get('/attendance-list', [App\Http\Controllers\frontendController\attendanceController::class, 'attendanceList'])->name('attendanceList');
    Route::get('/leave-approver', [App\Http\Controllers\frontendController\leaveController::class, 'leaveApprover'])->name('leaveApprover');
    Route::get('/add-leave-approver', [App\Http\Controllers\frontendController\leaveController::class, 'addLeaveApprover'])->name('addLeaveApprover');
    
    //Office Notice
    Route::get('/office-notice', [App\Http\Controllers\frontendController\officeNoticeController::class, 'officeNotice'])->name('officeNotice');
    Route::get('/addOfficeNotice', [App\Http\Controllers\frontendController\officeNoticeController::class, 'addOfficeNotice'])->name('addOfficeNotice');
    Route::post('/createOfficeNotice', [App\Http\Controllers\frontendController\officeNoticeController::class, 'createOfficeNotice'])->name('createOfficeNotice');
    Route::get('/showEditOfficeNotice/{id}', [App\Http\Controllers\frontendController\officeNoticeController::class, 'showEditOfficeNotice'])->name('showEditOfficeNotice');
    Route::post('/editOfficeNotice/{id}', [App\Http\Controllers\frontendController\officeNoticeController::class, 'editOfficeNotice'])->name('editOfficeNotice');
    
    Route::get('/editAttendance/{id}', [App\Http\Controllers\frontendController\attendanceController::class, 'editAttendance'])->name('editAttendance');

    Route::get('/leave-application-list',[\App\Http\Controllers\frontendController\leaveController::class,'allLeaveApplication'])->name('allLeaveApplication');

    //bulk employee add 
    Route::get('/uploadexcel', [App\Http\Controllers\reportController::class, 'uploadexcel']);
    Route::get('/salary-setting', [App\Http\Controllers\frontendController\salarySettingController::class, 'salarySetting'])->name('salarySetting');
    Route::post('/create-salary-setting', [App\Http\Controllers\frontendController\salarySettingController::class, 'createSalarySetting'])->name('createSalarySetting');

    //payslip 
    Route::get('/payslip', [App\Http\Controllers\frontendController\payslipController::class, 'payslip'])->name('payslip');
    
    //increment
    Route::get('/increment', [App\Http\Controllers\frontendController\incrementController::class, 'increment'])->name('increment');
    Route::get('/increment-history', [App\Http\Controllers\frontendController\incrementController::class, 'incrementHistory'])->name('incrementHistory');
    
    //timeline 
    Route::get('/timeline-setting', [App\Http\Controllers\frontendController\timelineController::class, 'timelineSetting'])->name('timelineSetting');
    Route::get('/employee-wise-timeline', [App\Http\Controllers\frontendController\timelineController::class, 'employeeWiseTimeline'])->name('employeeWiseTimeline');

    //custom report 
    Route::get('/custom-report', [App\Http\Controllers\frontendController\timelineController::class, 'customReport'])->name('customReport');

    //dashboard
    Route::get('/dashboard', [App\Http\Controllers\frontendController\timeWiseController::class, 'dashboard'])->name('dashboard');

});
 //dept excel download
 Route::get('/export-dept-data', [App\Http\Controllers\frontendController\departmentController::class, 'exportDeptData'])->name('downloadDeptExcel');

//designation excel download
Route::get('/export-desig-data', [App\Http\Controllers\frontendController\designationController::class, 'exportDesigData'])->name('downloadDesigExcel');

Route::get('/resetPassword', [App\Http\Controllers\forgetPasswordEmailController::class, 'resetPassword'])->name('resetPassword');
Route::get('/privacy-policy', [App\Http\Controllers\frontendController\companyController::class, 'privacyPolicy']);

