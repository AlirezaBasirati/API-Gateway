<?php

use App\Services\RouterService\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('authentication')->group(function () {
        Route::any('{platform}/v{version}/{service}/{any?}', [ServiceController::class, 'reroute'])->where('any', '.*');
    });
});

Route::get('storage/{any}', [ServiceController::class, 'rerouteMedia'])->where('any', '.*');


Route::middleware('web')->prefix('web')->group(function () {
    Route::get('{service}/{any?}', [ServiceController::class, 'rerouteWeb'])->where('any', '.*');
});
