<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// for authentication
Route::group(['prefix' => ''], function (){
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['middleware' => ['auth:sanctum']], function () {

// user
    Route::apiResource('user', UserController::class)->except('store');

// for organizations
    Route::apiResource('organization', OrganizationController::class);

// for vacancies
    Route::apiResource('vacancies', VacancyController::class);
    Route::post('vacancy-book/{vacancies}', [VacancyController::class, 'book']);
    Route::post('vacancy-unbook/{vacancies}/{user}', [VacancyController::class, 'unbook']);

// stats
    Route::get('stats/organizations', [StatsController::class, 'organizations']);
    Route::get('stats/users', [StatsController::class, 'users']);
    Route::get('stats/vacancies', [StatsController::class, 'vacancies']);
});
