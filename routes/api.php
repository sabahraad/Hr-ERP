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
    
    //Auth
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/forget-password', [App\Http\Controllers\forgetPasswordEmailController::class, 'forgetPassword']);

    //User Deatils
    Route::post('/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::get('/unauthorized',[App\Http\Controllers\Controller::class, 'unauthorized'])->name('unauthorized');
    Route::get('/user-profile', [App\Http\Controllers\AuthController::class, 'userProfile']);   

    //Attendance
    Route::post('/attendance', [App\Http\Controllers\attendanceController::class,'createAttendance'])->name('attendance');
    Route::get('/attendance-list', [App\Http\Controllers\attendanceController::class, 'showattendance'])->name('showattendance');
    Route::get('weekend-list', [App\Http\Controllers\weekendController::class, 'WeekendList'])->name('WeekendList');

    Route::post('/apply-leave', [App\Http\Controllers\leaveController::class, 'createLeaveApplications'])->name('createLeaveApplications');
    Route::get('/currentDateStatus', [App\Http\Controllers\attendanceController::class, 'currentDateStatus'])->name('currentDateStatus');
    Route::post('/update-reason', [App\Http\Controllers\attendanceController::class, 'updateReason'])->name('updateReason');
    Route::get('leave-type-list', [App\Http\Controllers\leaveController::class, 'leaveTypeList'])->name('leaveTypeList');
    Route::get('holiday-list', [App\Http\Controllers\holidayController::class, 'HolidayList'])->name('HolidayList');
    Route::get('available-leave-list', [App\Http\Controllers\leaveController::class, 'availableLeaveListforEmployee'])->name('availableLeaveListforEmployee');
    Route::get('/leave-applications-list/{id}', [App\Http\Controllers\leaveController::class, 'leaveApplicationsList'])->name('leaveApplicationsList');
    Route::get('/leave-approve-list', [App\Http\Controllers\leaveController::class, 'leaveApproveList']);
    Route::post('/approve-leave', [App\Http\Controllers\leaveController::class, 'approveLeave']);
    Route::post('/forget-password-email', [App\Http\Controllers\forgetPasswordEmailController::class, 'emailData']);
    Route::post('/verify-OTP', [App\Http\Controllers\forgetPasswordEmailController::class, 'verifyOTP']);
    Route::delete('/delete-attendance/{id}', [App\Http\Controllers\attendanceController::class, 'deleteAttendance']);
    Route::get('/current-day-attendance-status', [App\Http\Controllers\attendanceController::class, 'currentDayAttendanceStatus']);
    
    //expenses or TA/DA Bill 
    Route::get('/expenses-list', [App\Http\Controllers\expensesController::class, 'expensesList']);
    Route::post('/create-expenses', [App\Http\Controllers\expensesController::class, 'createExpenses']);
    Route::post('/edit-expenses/{id}', [App\Http\Controllers\expensesController::class, 'editExpenses']);
    Route::get('/catagory-list', [App\Http\Controllers\expensesController::class, 'catagoryList']);

    //Per month calculation for App
    Route::post('/month-wise-off-day-list', [App\Http\Controllers\leaveController::class, 'monthWiseOffDayList']);
    Route::post('/month-wise-report', [App\Http\Controllers\leaveController::class, 'monthWiseReport']);

    //deleteLeaveApplication
    Route::delete('/delete-leave-application/{id}', [App\Http\Controllers\leaveController::class, 'deleteLeaveApplication']);
    Route::post('/leave-approved-by-HR', [App\Http\Controllers\leaveController::class, 'leaveApprovedByHR']);

    //employee 
    
    Route::get('/employee-list', [App\Http\Controllers\employeeController::class, 'employeeList'])->name('employeeList');
    Route::post('/employee-edit/{id}', [App\Http\Controllers\employeeController::class, 'employeeEditApp']);
    Route::get('/employee-details/{id}', [App\Http\Controllers\employeeController::class, 'employeeDetails']);
    Route::get('/emp-list', [App\Http\Controllers\employeeController::class, 'empList']);

    //notice
    Route::get('/notice-list', [App\Http\Controllers\officeNoticeController::class, 'noticeList']);

    //MOTIVATION QUOTE
    Route::post('/saveMotivationalQuote', [App\Http\Controllers\motivationalSpeechController::class, 'saveMotivationalQuote']);

    //MOCK DETAILS
    Route::post('/save-mock-person-details', [App\Http\Controllers\AuthController::class, 'saveMockPersonDetails']);
    Route::get('/show-mock-person-details', [App\Http\Controllers\AuthController::class, 'showMockPersonDetails']);
    
    //Visit
    Route::post('/create-visit', [App\Http\Controllers\visitController::class, 'createVisit']);
    Route::get('/visit-list', [App\Http\Controllers\visitController::class, 'visitList']);
    Route::get('/individual-visit-deatils/{id}', [App\Http\Controllers\visitController::class, 'individualVisitDeatils']);
    Route::post('/complete-visit/{id}', [App\Http\Controllers\visitController::class, 'completeVisit']);
    Route::post('/edit-visit/{id}', [App\Http\Controllers\visitController::class, 'editVisit']);
    Route::get('/complete-visit-list', [App\Http\Controllers\visitController::class, 'completeVisitList']);

    //meeting
    Route::post('/create-meeting', [App\Http\Controllers\meetingController::class, 'createMeeting']);
    Route::get('/meeting-list', [App\Http\Controllers\meetingController::class, 'meetingList']);
    Route::post('/edit-meeting/{id}', [App\Http\Controllers\meetingController::class, 'editMeeting']);
    Route::get('/creator-meeitng-list', [App\Http\Controllers\meetingController::class, 'creatorMeeitngList']);
    Route::get('/meeitng-histroy', [App\Http\Controllers\meetingController::class, 'meetingHistroy']);
    Route::get('/meeitng-details/{id}', [App\Http\Controllers\meetingController::class, 'meetingDetails']);

    //payslip user end
    Route::get('/payslip-list', [App\Http\Controllers\SalaryController::class, 'payslipList']);
    Route::get('/payslip-details/{id}', [App\Http\Controllers\SalaryController::class, 'payslipDetails']);
    Route::get('/payslip-list-companywise/{month}/{year}', [App\Http\Controllers\SalaryController::class, 'payslipListCompanyWise']);

});

//All the routes are only accessable for HR 

Route::middleware([RoleCheck::class,SetDefaultJsonResponse::class])->group(function () {

//company
Route::get('/company-details', [App\Http\Controllers\companyController::class, 'showCompany'])->name('showCompany');
Route::post('/edit/company', [App\Http\Controllers\companyController::class, 'editCompany'])->name('editCompany');
Route::delete('/delete/company', [App\Http\Controllers\companyController::class, 'deleteCompany'])->name('deleteCompany');

//department
Route::post('/add-department', [App\Http\Controllers\departmentController::class, 'addDepartment'])->name('addDepartment');
Route::get('/department-list', [App\Http\Controllers\departmentController::class, 'showDepartment'])->name('showDepartment');
Route::post('/edit/department/{id}', [App\Http\Controllers\departmentController::class, 'editDepartment'])->name('editDepartment');
Route::delete('/delete/department/{id}', [App\Http\Controllers\departmentController::class, 'deleteDepartment'])->name('deleteDepartment');

//designations
Route::post('/add-designations', [App\Http\Controllers\designationsController::class, 'addDesignations'])->name('addDesignations');
Route::get('/designations-list/{id}', [App\Http\Controllers\designationsController::class, 'showDesignations'])->name('showDesignations');
Route::post('/edit/designations/{id}', [App\Http\Controllers\designationsController::class, 'editDesignations'])->name('editDesignations');
Route::delete('/delete/designations/{id}', [App\Http\Controllers\designationsController::class, 'deleteDesignations'])->name('deleteDesignations');

//IP
Route::post('/add-IP', [App\Http\Controllers\IpController::class, 'addIP'])->name('addIP');
Route::get('/IP-list', [App\Http\Controllers\IpController::class, 'showIP'])->name('showIP');
Route::delete('/delete/IP', [App\Http\Controllers\IpController::class, 'deleteIP'])->name('deleteIP');

Route::post('/edit/attendance/{id}', [App\Http\Controllers\attendanceController::class, 'updateattendance'])->name('updateattendance');

//Leave-type
Route::post('add-leave-type', [App\Http\Controllers\leaveController::class, 'addleavetype'])->name('addleavetype');
Route::post('/edit/leave-type/{id}', [App\Http\Controllers\leaveController::class, 'updateleavetype'])->name('updateleavetype');
Route::delete('/delete/leave-type/{id}', [App\Http\Controllers\leaveController::class, 'deleteleaveType'])->name('deleteleaveType');

//Employee
Route::post('add-employee', [App\Http\Controllers\employeeController::class, 'addEmployee'])->name('addEmployee');
Route::get('all-employee-list', [App\Http\Controllers\employeeController::class, 'employeeListForAdminPanel'])->name('employeeListForAdminPanel');
Route::post('/edit/employee/{id}', [App\Http\Controllers\employeeController::class, 'updateEmployee'])->name('updateEmployee');
Route::delete('/delete/employee/{id}', [App\Http\Controllers\employeeController::class, 'deleteEmployee'])->name('deleteEmployee');

//Office Location
Route::post('add-office-location', [App\Http\Controllers\officeLocationController::class, 'createOfficeLocation'])->name('createOfficeLocation');
Route::get('office-location-list', [App\Http\Controllers\officeLocationController::class, 'OfficeLocationList'])->name('OfficeLocationList');
Route::post('/edit/office-location/{id}', [App\Http\Controllers\officeLocationController::class, 'updateOfficeLocation'])->name('updateOfficeLocation');
Route::delete('/delete/office-location/{id}', [App\Http\Controllers\officeLocationController::class, 'deleteOfficeLocation'])->name('deleteOfficeLocation');

//Weekend List
Route::post('add-weekend', [App\Http\Controllers\weekendController::class, 'createWeekend'])->name('createWeekend');
// Route::get('weekend-list', [App\Http\Controllers\weekendController::class, 'WeekendList'])->name('WeekendList');
Route::delete('/delete/weekend', [App\Http\Controllers\weekendController::class, 'deleteWeekend'])->name('deleteWeekend');

//Holiday
Route::post('add-holiday', [App\Http\Controllers\holidayController::class, 'createHoliday'])->name('createHoliday');
Route::post('/edit/holiday/{id}', [App\Http\Controllers\holidayController::class, 'updateHoliday'])->name('updateHoliday');
Route::post('/delete/holiday/{id}', [App\Http\Controllers\holidayController::class, 'deleteHoliday'])->name('deleteHoliday');

//Office-hour / officeSrtting
Route::post('add-office-hour', [App\Http\Controllers\attendanceSettingsController::class, 'createOfficeHour'])->name('createOfficeHour');
Route::get('office-hour-list', [App\Http\Controllers\attendanceSettingsController::class, 'officeHourList'])->name('officeHourList');
Route::delete('/delete/office-hour', [App\Http\Controllers\attendanceSettingsController::class, 'deleteOfficeHour'])->name('deleteOfficeHour');

//Attendance Type
Route::post('add-attendance-type', [App\Http\Controllers\attendanceTypeController::class, 'createAttendanceType'])->name('createAttendanceType');
Route::get('attendance-type-details', [App\Http\Controllers\attendanceTypeController::class, 'AttendanceTypeList'])->name('AttendanceTypeList');
Route::delete('/delete/attendance-type', [App\Http\Controllers\attendanceTypeController::class, 'deleteAttendanceType'])->name('deleteAttendanceType');

Route::delete('/delete/attendance/{id}', [App\Http\Controllers\attendanceController::class, 'deleteattendance'])->name('deleteattendance');

//office Notice
Route::post('add-notice', [App\Http\Controllers\officeNoticeController::class, 'addNotice']);
Route::post('edit-notice/{id}', [App\Http\Controllers\officeNoticeController::class, 'editNotice']);
Route::delete('/delete/notice/{id}', [App\Http\Controllers\officeNoticeController::class, 'deleteNotice']);

//leave Approver
Route::get('/approvers-list/{id}', [App\Http\Controllers\approversController::class, 'approversList']);
Route::post('/add-approvers', [App\Http\Controllers\approversController::class, 'addApprovers']);
Route::post('/edit-approvers/{id}', [App\Http\Controllers\approversController::class, 'editApprovers']);
Route::delete('/delete-approvers/{id}', [App\Http\Controllers\approversController::class, 'deleteApprovers']);

//expenses or TA/DA Bill 

Route::post('/approve-expense', [App\Http\Controllers\expensesController::class, 'approveExpense']);
Route::get('/all-expenses-list', [App\Http\Controllers\expensesController::class, 'allExpensesList']);
Route::delete('/delete-expenses/{id}', [App\Http\Controllers\expensesController::class, 'deleteExpenses']);

//attendanceAddedByHR
Route::post('/attendance-add-by-HR', [App\Http\Controllers\attendanceController::class, 'attendanceAddedByHR']);
Route::get('/absent-employee', [App\Http\Controllers\attendanceController::class, 'absentEmployee']);
Route::post('/present-employee-list', [App\Http\Controllers\attendanceController::class, 'presentEmployeeList']);
Route::post('/attendance-edited-by-HR', [App\Http\Controllers\attendanceController::class, 'attendanceEditedByHr']);

//Leave 
Route::get('/all-leave-application-only-for-hr', [App\Http\Controllers\leaveController::class, 'allLeaveApplication']);
Route::get('/leave-application-details/{id}', [App\Http\Controllers\leaveController::class, 'leaveApplicationDetails']);

//salary setting
Route::post('/create-salary-setting', [App\Http\Controllers\salarySettingController::class, 'createSalarySetting']);
Route::get('/show-salary-setting', [App\Http\Controllers\salarySettingController::class, 'showSalarySetting']);
Route::post('/edit-salary-setting/{id}', [App\Http\Controllers\salarySettingController::class, 'editSalarySetting']);
Route::delete('/delete-salary-setting/{id}', [App\Http\Controllers\salarySettingController::class, 'deleteSalarySetting']);

//Salary increment 
Route::get('/employee-salary-details', [App\Http\Controllers\SalaryController::class, 'employeeSalaryDetails']);
Route::get('/individual-employee-salary-details/{id}', [App\Http\Controllers\SalaryController::class, 'individualEmployeeSalaryDetails']);
Route::post('/change-employee-salary/{id}', [App\Http\Controllers\SalaryController::class, 'changeEmployeeSalary']);
Route::get('/salary-list', [App\Http\Controllers\SalaryController::class, 'salaryList']);
Route::get('/employee-salary-history/{id}', [App\Http\Controllers\SalaryController::class, 'employeeSalaryHistory']);


//Payslip 
Route::post('/create-payslip', [App\Http\Controllers\SalaryController::class, 'createPayslip']);
Route::post('/adjust-payslip/{id}', [App\Http\Controllers\SalaryController::class, 'adjustPayslip']);


//Report for admin panel
Route::post('/custom-attendance-report', [App\Http\Controllers\reportController::class, 'customAttendanceReport']);

//bulk employee add 
Route::post('/upload-employees',[App\Http\Controllers\employeeController::class, 'uploadEmployees']);

//temp salary setting 
Route::post('/temp-salary-setting',[App\Http\Controllers\salarySettingController::class, 'tempSalarySetting']);
Route::get('/temp-salary-setting-list',[App\Http\Controllers\salarySettingController::class, 'tempSalarySettingList']);
Route::delete('/delete-temp-salary-setting/{id}',[App\Http\Controllers\salarySettingController::class, 'deletetempSalarySetting']);

//Timeline Setting
Route::post('/add-timeline', [App\Http\Controllers\timelineController::class, 'addTimeline']);
Route::get('/timeline-list', [App\Http\Controllers\timelineController::class, 'timelineList']);
Route::post('/edit-timeine/{id}', [App\Http\Controllers\timelineController::class, 'editTimeline']);
Route::delete('/delete-timeline/{id}', [App\Http\Controllers\timelineController::class, 'deleteTimeline']);
Route::get('/individual-timeline-list/{id}', [App\Http\Controllers\timelineController::class, 'individualTimelineList']);


//Timeline Track
Route::post('/store-timeline-track', [App\Http\Controllers\timelineController::class, 'storeTimelineTrack']);
Route::post('/date-wise-track-of-employee', [App\Http\Controllers\timelineController::class, 'dateWiseTrackOfEmployee']);
Route::post('/employee-wise-timeline-list', [App\Http\Controllers\timelineController::class, 'employeeWiseTimelineList']);


});
Route::get('/department-name-list',[App\Http\Controllers\departmentController::class, 'departmentNameList'])->name('departmentNameList');
Route::get('/designation-name-list/{id}',[App\Http\Controllers\designationsController::class, 'designationNameList'])->name('designationNameList');

//motivationalSpeech
Route::get('/motivationalSpeech', [App\Http\Controllers\motivationalSpeechController::class, 'motivationalSpeech']);
