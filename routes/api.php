<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/addCompany', [App\Http\Controllers\companyController::class, 'addCompany'])->name('addCompany');
Route::get('/showCompany', [App\Http\Controllers\companyController::class, 'showCompany'])->name('showCompany');
Route::post('/editCompany/{id}', [App\Http\Controllers\companyController::class, 'editCompany'])->name('editCompany');
Route::delete('/deleteCompany/{id}', [App\Http\Controllers\companyController::class, 'deleteCompany'])->name('deleteCompany');

Route::post('/addDepartment', [App\Http\Controllers\departmentController::class, 'addDepartment'])->name('addDepartment');
Route::get('/showDepartment', [App\Http\Controllers\departmentController::class, 'showDepartment'])->name('showDepartment');
Route::post('/editDepartment/{id}', [App\Http\Controllers\departmentController::class, 'editDepartment'])->name('editDepartment');
Route::delete('/deleteDepartment/{id}', [App\Http\Controllers\departmentController::class, 'deleteDepartment'])->name('deleteDepartment');

Route::post('/addDesignations', [App\Http\Controllers\designationsController::class, 'addDesignations'])->name('addDesignations');
Route::get('/showDesignations', [App\Http\Controllers\designationsController::class, 'showDesignations'])->name('showDesignations');
Route::post('/editDesignations/{id}', [App\Http\Controllers\designationsController::class, 'editDesignations'])->name('editDesignations');
Route::delete('/deleteDesignations/{id}', [App\Http\Controllers\designationsController::class, 'deleteDesignations'])->name('deleteDesignations');

Route::get('/adminShow', [App\Http\Controllers\companyController::class, 'adminShow'])->name('adminShow');

