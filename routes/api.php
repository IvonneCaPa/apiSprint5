<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('auth.login', [AuthController::class, 'login'])->name('api.login');
Route::post('auth.register', [AuthController::class, 'register'])->name('api.register');

Route::get('activities', [ActivityController::class, 'index'])->name('api.activities.index');

Route::middleware('auth:api')->group(function()
    {
        Route::get('auth.me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::get('auth.logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    }
);

