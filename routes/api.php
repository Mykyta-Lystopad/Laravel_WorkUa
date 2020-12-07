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
Route::group(['prefix' => 'auth'], function (){
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['middleware' => ['auth:sanctum']], function () {

// user
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

// for organizations
    Route::apiResource('organization', OrganizationController::class);

// for vacancies
    Route::apiResource('vacancies', VacancyController::class);
    Route::post('vacancy-book/{vacancies}', [VacancyController::class, 'book']);
    Route::post('vacancy-unbook/{vacancies}', [VacancyController::class, 'unbook'])->name('unbook');
//    Route::get('vacancies/{vacancies}', [VacancyController::class, 'index']);
//    Route::get('vacancies/', [VacancyController::class, 'show']);
//    Route::post('vacancies/{vacancies}', [VacancyController::class, 'store']);
//    Route::put('vacancies/{vacancies}', [VacancyController::class, 'update']);
//    Route::delete('vacancies/{vacancies}', [VacancyController::class, 'destroy']);

// stats
    Route::get('stats/organizations', [StatsController::class, 'organizations']);
    Route::get('stats/users', [StatsController::class, 'users']);


});



//Route::get('cashboxes', [CashBoxController::class, 'index']);
//Route::post('cashboxes', [CashBoxController::class, 'store']);
//Route::get('cashboxes/{cashbox}', [CashBoxController::class, 'show']);
//Route::put('cashboxes/{cashbox}', [CashBoxController::class, 'update']);
//Route::delete('cashboxes/{cashbox}', [CashBoxController::class, 'destroy']);

// or

//Route::apiResource('cashboxes', CashBoxController::class);
//Route::apiResource('cashboxes', CashBoxController::class)->except('store');
//Route::apiResource('cashboxes', CashBoxController::class)->only('index');
