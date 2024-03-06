<?php

use Illuminate\Http\Request;
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

Route::get('/fib', function (Request $request) {
    return fibonacci($request->get('n', 20));
});

function fibonacci($number){
    if ($number == 0)
        return 0;
    else if ($number == 1)
        return 1;
    else
        return (fibonacci($number-1) +
            fibonacci($number-2));
}
