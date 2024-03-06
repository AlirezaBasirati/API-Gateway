<?php

use App\Services\AuthenticationService\V1\Controllers\Internal\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {


        Route::prefix('internal')->name('internal.')->group(function () {
            Route::prefix('auth')->name('auth.')->group(function () {

                Route::post('admin-login', [AuthenticationController::class, 'adminLogin'])->name('admin-login');
                Route::post('app-login', [AuthenticationController::class, 'appLogin'])->name('app-login');
                Route::post('admin-reset', [AuthenticationController::class, 'adminReset'])->name('admin-reset');
                Route::post('app-reset', [AuthenticationController::class, 'appReset'])->name('app-reset');
                Route::post('check', [AuthenticationController::class, 'check'])->name('check');
                Route::get('fetch', [AuthenticationController::class, 'fetch'])->name('fetch');
                Route::put('/', [AuthenticationController::class, 'appStore'])->name('store');
                Route::post('/refresh', [AuthenticationController::class, 'refresh'])->name('refresh');

                Route::post('picker-login', [AuthenticationController::class, 'pickerLogin'])->name('picker-login');

                Route::post('manager-login', [AuthenticationController::class, 'managerLogin'])->name('manager-login');

                Route::middleware('authentication')->group(function () {
                    Route::get('me', [AuthenticationController::class, 'me'])->name('me');
                    Route::delete('logout', [AuthenticationController::class, 'logout'])->name('logout');
                    Route::patch('app-change-password', [AuthenticationController::class, 'appChangePassword'])->name('app-change-password');
                    Route::patch('admin-change-password', [AuthenticationController::class, 'adminChangePassword'])->name('admin-change-password');
                });

            });
        });
    });
