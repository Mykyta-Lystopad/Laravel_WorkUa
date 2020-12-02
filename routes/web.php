<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [OrganizationController::class, 'indexWeb']);

Route::get('organization/indexWeb', [OrganizationController::class, 'indexWeb'])->name('organization.indexWeb');
Route::get('organization/vacancy/createWeb/{organization}', [VacancyController::class, 'createWeb'])->name('vacancy.createWeb');
Route::post('organization/vacancy/storeWeb/{id}', [VacancyController::class, 'storeWeb'])->name('vacancy.storeWeb');
Route::get('organization/createWeb', [OrganizationController::class, 'createWeb'])->name('organization.createWeb');
Route::post('organization/', [OrganizationController::class, 'storeWeb'])->name('organization.storeWeb');
Route::get('organization/vacancy/showWeb/{organization}', [VacancyController::class, 'showWeb'])->name('vacancy.showWeb');
Route::delete('vacancy/{id}', [VacancyController::class, 'destroyWeb'])->name('vacancy.destroyWeb');
Route::delete('organization/{id}', [OrganizationController::class, 'destroyWeb'])->name('organization.destroyWeb');
