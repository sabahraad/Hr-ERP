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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registration-form', [App\Http\Controllers\FrontendController\AuthController::class, 'registrationForm'])->name('registrationForm');

Route::post('/registration', [App\Http\Controllers\FrontendController\AuthController::class, 'registration'])->name('registration');
Route::get('/login-form', [App\Http\Controllers\FrontendController\AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [App\Http\Controllers\FrontendController\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\FrontendController\AuthController::class, 'logout'])->name('logout');

Route::get('/holidays', [App\Http\Controllers\FrontendController\HolidaysController::class, 'holidays'])->name('holidays');
Route::get('/designation', [App\Http\Controllers\FrontendController\designationController::class, 'designation'])->name('designation');

Route::get('/leaveList', [App\Http\Controllers\FrontendController\leaveController::class, 'leaveList'])->name('leaveList');


Route::post('/addCompany', [App\Http\Controllers\companyController::class, 'addCompany'])->name('addCompany');

Route::get('/showCompany', [App\Http\Controllers\companyController::class, 'showCompany'])->name('showCompany');

Route::get('/test', [App\Http\Controllers\FrontendController\AuthController::class, 'test'])->name('test');
// Route::get('/holidays', [App\Http\Controllers\FrontendController\AuthController::class, 'holidays'])->name('holidays');

Route::get('/company', [App\Http\Controllers\FrontendController\companyController::class, 'company'])->name('company');



// Route::post('/add-department', [App\Http\Controllers\frontendController\departmentController::class, 'addDepartment'])->name('addDepartment');
// Route::get('/department', [App\Http\Controllers\FrontendController\departmentController::class, 'department'])->name('department');
// Route::post('/edit/department/{id}', [App\Http\Controllers\FrontendController\departmentController::class, 'editDepartment'])->name('editDepartment');
// Route::post('/delete/department/{id}', [App\Http\Controllers\FrontendController\departmentController::class, 'deleteDepartment'])->name('deleteDepartment');

Route::group(['middleware' => 'check_access_token'], function () {

    Route::post('/add-department', [App\Http\Controllers\frontendController\departmentController::class, 'addDepartment'])->name('addDepartment');
    Route::get('/department', [App\Http\Controllers\FrontendController\departmentController::class, 'department'])->name('department');
    Route::post('/edit/department/{id}', [App\Http\Controllers\FrontendController\departmentController::class, 'editDepartment'])->name('editDepartment');
    Route::post('/delete/department/{id}', [App\Http\Controllers\FrontendController\departmentController::class, 'deleteDepartment'])->name('deleteDepartment');

});