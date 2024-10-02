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


Route::group(['middleware' => ['check_access_token' ,'prevent-back-history']], function () {
    Route::get('/password-change', [App\Http\Controllers\frontendController\AuthController::class, 'showPasswordChange'])->name('showPasswordChange');
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
    Route::get('/individual-attendance-report/{id}/{startDate}/{endDate}', [App\Http\Controllers\frontendController\attendanceController::class, 'individualAttendanceReport'])->name('individualAttendanceReport');

    Route::get('/leave-report', [App\Http\Controllers\frontendController\leaveController::class, 'leaveReport'])->name('leaveReport');

    
    //dashboard
    Route::get('/dashboard', [App\Http\Controllers\frontendController\timeWiseController::class, 'dashboard'])->name('dashboard');
    Route::get('/presentEmployeeList', [App\Http\Controllers\frontendController\timeWiseController::class, 'presentEmployeeList'])->name('presentEmployeeList');
    Route::get('/absentEmployeeList', [App\Http\Controllers\frontendController\timeWiseController::class, 'absentEmployeeList'])->name('absentEmployeeList');
    Route::get('/leaveEmployeeList', [App\Http\Controllers\frontendController\timeWiseController::class, 'leaveEmployeeList'])->name('leaveEmployeeList');

    
    //Shift
    Route::get('/shift-list', [App\Http\Controllers\frontendController\shiftController::class, 'ShiftList'])->name('ShiftList');
    Route::get('/add-shift', [App\Http\Controllers\frontendController\shiftController::class, 'addShift'])->name('addShift');
    Route::post('/create-shift', [App\Http\Controllers\frontendController\shiftController::class, 'createShift'])->name('createShift');
    Route::get('/edit-shift/{id}', [App\Http\Controllers\frontendController\shiftController::class, 'showEditShift'])->name('showEditShift');
    Route::post('/edit-shift', [App\Http\Controllers\frontendController\shiftController::class, 'editShift'])->name('editShift');
    Route::get('/delete-shift/{id}', [App\Http\Controllers\frontendController\shiftController::class, 'deleteShift'])->name('deleteShift');
    
    //add employee in shift
    Route::get('/show-add-employee-in-shift', [App\Http\Controllers\frontendController\shiftController::class, 'showAddEmployeeInShift'])->name('showAddEmployeeInShift');
    Route::post('/add-employee-in-shift', [App\Http\Controllers\frontendController\shiftController::class, 'addEmployeeInShift'])->name('addEmployeeInShift');
    
    //remove employee from shift
    Route::get('/show-remove-employee-from-shift/{id}', [App\Http\Controllers\frontendController\shiftController::class, 'showRemoveEmployeeFromShift'])->name('showRemoveEmployeeFromShift');
    Route::post('/remove-employee-from-shift', [App\Http\Controllers\frontendController\shiftController::class, 'removeEmployeeFromShift'])->name('removeEmployeeFromShift');
    
    //Expenses
    Route::get('/expense-list', [App\Http\Controllers\frontendController\expenseController::class, 'expenseList'])->name('expenseList');
    Route::get('/expense-report', [App\Http\Controllers\frontendController\expenseController::class, 'expensesReport'])->name('expensesReport');
    Route::get('/individual-expense-report/{id}/{startDate}/{endDate}', [App\Http\Controllers\frontendController\expenseController::class, 'individualExpenseReport'])->name('individualExpenseReport');

    //multi location 
    Route::get('/Loction-wise-employee-list', [App\Http\Controllers\frontendController\locationWiseEmployeeController::class, 'LoctionWiseEmployeeList'])->name('LoctionWiseEmployeeList');
    Route::post('/Loction-wise-employee-list', [App\Http\Controllers\frontendController\locationWiseEmployeeController::class, 'individualLoctionWiseEmployeeList'])->name('individualLoctionWiseEmployeeList');
    Route::post('/add-employee-into-location', [App\Http\Controllers\frontendController\locationWiseEmployeeController::class, 'addEmployeeIntoLocation'])->name('addEmployeeIntoLocation');
    Route::get('/edit-employee-into-location/{id}', [App\Http\Controllers\frontendController\locationWiseEmployeeController::class, 'editEmployeeIntoLocation'])->name('editEmployeeIntoLocation');
    Route::post('/update-employee-into-location', [App\Http\Controllers\frontendController\locationWiseEmployeeController::class, 'updateEmployeeIntoLocation'])->name('updateEmployeeIntoLocation');

    //Remote Employee
    Route::get('/remote-employee-list', [App\Http\Controllers\frontendController\remoteEmployeeController::class, 'remoteEmployeeList'])->name('remoteEmployeeList');
    Route::post('/add-employee-into-remote', [App\Http\Controllers\frontendController\remoteEmployeeController::class, 'addEmployeeIntoRemote'])->name('addEmployeeIntoRemote');
    Route::get('/edit-employee-into-remote/{id}', [App\Http\Controllers\frontendController\remoteEmployeeController::class, 'editEmployeeIntoRemote'])->name('editEmployeeIntoRemote');
    Route::post('/update-employee-into-remote', [App\Http\Controllers\frontendController\remoteEmployeeController::class, 'updateEmployeeIntoRemote'])->name('updateEmployeeIntoRemote');
    Route::post('/delete-employee-into-remote', [App\Http\Controllers\frontendController\remoteEmployeeController::class, 'deleteEmployeeIntoRemote'])->name('deleteEmployeeIntoRemote');

    // showVisitReport
    Route::get('/visit-report', [App\Http\Controllers\frontendController\visitController::class, 'showVisitReport'])->name('showVisitReport');

});
 //dept excel download
 Route::get('/export-dept-data', [App\Http\Controllers\frontendController\departmentController::class, 'exportDeptData'])->name('downloadDeptExcel');

//designation excel download
Route::get('/export-desig-data', [App\Http\Controllers\frontendController\designationController::class, 'exportDesigData'])->name('downloadDesigExcel');

Route::get('/resetPassword', [App\Http\Controllers\forgetPasswordEmailController::class, 'resetPassword'])->name('resetPassword');
Route::get('/privacy-policy', [App\Http\Controllers\frontendController\companyController::class, 'privacyPolicy']);


Route::group(['prefix' => 'super-admin','middleware' => ['super_admin_check' ,'prevent-back-history']], function () {
    //dashboard
    Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\dashboardController::class, 'dashboard'])->name('super-admin.dashboard');
    Route::get('/company-list', [App\Http\Controllers\SuperAdmin\companyController::class, 'companyList'])->name('super-admin.companyList');
    Route::get('/holiday-list', [App\Http\Controllers\SuperAdmin\companyController::class, 'holidayList'])->name('super-admin.holidayList');
    
    Route::get('/package-list', [App\Http\Controllers\SuperAdmin\packageController::class, 'packageList'])->name('super-admin.packageList');
    
   
    Route::post('/create-package', [App\Http\Controllers\SuperAdmin\packageController::class, 'createPackage'])->name('super-admin.createPackage');
    Route::get('/edit-package/{id}', [App\Http\Controllers\SuperAdmin\packageController::class, 'editPackageform'])->name('super-admin.editPackageform');
    Route::post('/edit-package/{id}', [App\Http\Controllers\SuperAdmin\packageController::class, 'editPackage'])->name('super-admin.editPackage');
    Route::post('/delete-package', [App\Http\Controllers\SuperAdmin\packageController::class, 'deletePackage'])->name('super-admin.deletePackage');

});

Route::group(['prefix' => 'admin','middleware' => ['admin_check','prevent-back-history']], function () {

    //category
    Route::get('/category-list', [App\Http\Controllers\Requisition\CategoryController::class, 'categoryList'])->name('categoryList');
    Route::post('/create-category', [App\Http\Controllers\Requisition\CategoryController::class, 'createCategory'])->name('createCategory');
    Route::get('/edit-category/{id}', [App\Http\Controllers\Requisition\CategoryController::class, 'categoryEdit'])->name('categoryEdit');
    Route::post('/update-category/{id}', [App\Http\Controllers\Requisition\CategoryController::class, 'categoryUpdate'])->name('categoryUpdate');
    Route::post('/delete-category', [App\Http\Controllers\Requisition\CategoryController::class, 'categoryDelete'])->name('categoryDelete');

    //product
    Route::get('/product-list', [App\Http\Controllers\Requisition\productController::class, 'productList'])->name('productList');
    Route::post('/create-product', [App\Http\Controllers\Requisition\productController::class, 'createProduct'])->name('createProduct');
    Route::get('/edit-product/{id}', [App\Http\Controllers\Requisition\productController::class, 'productEdit'])->name('productEdit');
    Route::post('/update-product/{id}', [App\Http\Controllers\Requisition\productController::class, 'productUpdate'])->name('productUpdate');
    Route::post('/delete-product', [App\Http\Controllers\Requisition\productController::class, 'productDelete'])->name('productDelete');
    Route::post('/find-product', [App\Http\Controllers\Requisition\productController::class, 'findProduct'])->name('findProduct');

    //vendor
    Route::get('/vendor-list', [App\Http\Controllers\Requisition\vendorController::class, 'vendorList'])->name('vendorList');
    Route::post('/create-vendor', [App\Http\Controllers\Requisition\vendorController::class, 'createVendor'])->name('createVendor');
    Route::get('/edit-vendor/{id}', [App\Http\Controllers\Requisition\vendorController::class, 'vendorEdit'])->name('vendorEdit');
    Route::post('/update-vendor/{id}', [App\Http\Controllers\Requisition\vendorController::class, 'vendorUpdate'])->name('vendorUpdate');
    Route::post('/delete-vendor', [App\Http\Controllers\Requisition\vendorController::class, 'vendorDelete'])->name('vendorDelete');
    Route::post('/find-vendor', [App\Http\Controllers\Requisition\vendorController::class, 'findVendor'])->name('findVendor');
    
    //requisition
    Route::get('/requisition-list', [App\Http\Controllers\Requisition\requisitionController::class, 'requisitionList'])->name('requisitionList');
    Route::post('/approve-requisition', [App\Http\Controllers\Requisition\requisitionController::class, 'approveRequisition'])->name('approveRequisition');

});
Route::group(['prefix' => 'director','middleware' => ['director_check' ,'prevent-back-history']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Director\dashboardController::class, 'dashboard'])->name('director.dashboard');
    Route::get('/requisition-list', [App\Http\Controllers\Director\dashboardController::class, 'requisitionList'])->name('director.requisitionList');
    Route::get('/presentEmployeeList', [App\Http\Controllers\Director\dashboardController::class, 'presentEmployeeList'])->name('director.presentEmployeeList');
    Route::get('/absentEmployeeList', [App\Http\Controllers\Director\dashboardController::class, 'absentEmployeeList'])->name('director.absentEmployeeList');

});
