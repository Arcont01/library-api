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

Route::name('api.')->group(function () {
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('auth.login');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('auth.register');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('auth.logout');
        Route::post('me', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('user.me');
        Route::apiResources([
            'books' => \App\Http\Controllers\BookController::class,
            'categories' => \App\Http\Controllers\BookController::class,
        ]);
    });
});
