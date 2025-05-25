<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('auth.login', [AuthController::class, 'login'])->name('api.login');
Route::post('auth.register', [AuthController::class, 'register'])->name('api.register');

Route::middleware('auth:api')->group(function()
    {
        Route::get('auth.user', [AuthController::class, 'user'])->name('api.auth.user');
    }
);
