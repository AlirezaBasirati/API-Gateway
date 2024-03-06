<?php

use App\Services\AuthorizationService\V1\Controllers\Admin\AuthorizationController;
use App\Services\AuthorizationService\V1\Controllers\Admin\PermissionController;
use App\Services\AuthorizationService\V1\Controllers\Admin\RoleController;
use App\Services\AuthorizationService\V1\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {

    Route::middleware('authentication')->group(function () {

        Route::prefix('internal')->name('internal.')->group(function () {

            Route::get('auth/permissions', [AuthorizationController::class, 'permissions'])->name('auth.permissions');

            Route::post('users/batch', [UserController::class, 'storeBatch'])->name('store-batch');
            Route::patch('users/{user}/roles', [UserController::class, 'syncRoles'])->name('sync-roles');
            Route::patch('users/{user}/permissions', [UserController::class, 'syncPermissions'])->name('sync-permissions');
            Route::apiResource('users', UserController::class);

            Route::apiResource('permissions', PermissionController::class);

            Route::patch('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('sync-permissions');
            Route::apiResource('roles', RoleController::class);
        });
    });
});
