<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HolidayPlanController;
use Illuminate\Support\Facades\Route;

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });

    // User CRUD Routes
    Route::middleware('auth:api')->group(function () {
        Route::get('users', [UserController::class, 'getAll'])->name('users.getAll');
        Route::post('users', [UserController::class, 'create'])->name('users.create');
        Route::get('users/{id}/show', [UserController::class, 'findById'])->name('users.findById');
        Route::get('users/{id}/trashed', [UserController::class, 'findByIdTrashed'])->name('users.findByIdTrashed');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'delete'])->name('users.delete');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::get('users/trashed', [UserController::class, 'getAllOnlyTrashed'])->name('users.getAllOnlyTrashed');
    });

    // Holiday Plan CRUD Routes
    Route::middleware('auth:api')->group(function () {
        Route::get('holiday-plans', [HolidayPlanController::class, 'getAll'])->name('holiday-plans.getAll');
        Route::post('holiday-plans', [HolidayPlanController::class, 'create'])->name('holiday-plans.create');
        Route::get('holiday-plans/{id}/show', [HolidayPlanController::class, 'findById'])->name('holiday-plans.findById');
        Route::get('holiday-plans/{id}/trashed', [HolidayPlanController::class, 'findByIdTrashed'])->name('holiday-plans.findByIdTrashed');
        Route::put('holiday-plans/{id}', [HolidayPlanController::class, 'update'])->name('holiday-plans.update');
        Route::delete('holiday-plans/{id}', [HolidayPlanController::class, 'delete'])->name('holiday-plans.delete');
        Route::post('holiday-plans/{id}/restore', [HolidayPlanController::class, 'restore'])->name('holiday-plans.restore');
        Route::get('holiday-plans/trashed', [HolidayPlanController::class, 'getAllOnlyTrashed'])->name('holiday-plans.getAllOnlyTrashed');
        Route::get('/holiday-plan/{id}/pdf', [HolidayPlanController::class, 'generateHolidayPlanPDF'])->name('holiday-plans.pdf');
    });

});
