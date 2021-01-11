<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// for authentication
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('resend/{email}', [AuthController::class, 'resendVerificationEmail'])->name('resend');
Route::post('resendPassword/{email}', [AuthController::class, 'resendPassword'])->name('resendPassword');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {

// user
    Route::apiResource('user', UserController::class)->except('store');

// for organizations
    Route::apiResource('organization', OrganizationController::class);
    Route::post('createOrganizationForEmployer', [OrganizationController::class, 'storeForEmployers']);

// for vacancies
    Route::apiResource('vacancy', VacancyController::class);
    Route::post('vacancy-book', [VacancyController::class, 'book']);
    Route::post('vacancy-unbook', [VacancyController::class, 'unbooked']);

// stats
    Route::get('stats/organizations', [StatsController::class, 'organizations']);
    Route::get('stats/users', [StatsController::class, 'users']);
    Route::get('stats/vacancies', [StatsController::class, 'vacancies']);
});
