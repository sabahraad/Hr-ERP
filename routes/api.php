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
Route::post('/editCompany', [App\Http\Controllers\companyController::class, 'editCompany'])->name('editCompany');
Route::post('/deleteCompany/{id}', [App\Http\Controllers\companyController::class, 'deleteCompany'])->name('deleteCompany');

