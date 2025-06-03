<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ActivityController;
    use App\Http\Controllers\GalleryController;
    use App\Http\Controllers\PhotoController;
    use App\Http\Controllers\UserController;

    // Rutas públicas de autenticación
    Route::post('auths.login', [AuthController::class, 'login'])->name('api.auths.login');
    Route::post('auths.register', [AuthController::class, 'register'])->name('api.auths.register');

    // Rutas públicas para consultar
    Route::get('activities', [ActivityController::class, 'index'])->name('api.activities.index');
    Route::get('activities.{activity}', [ActivityController::class, 'show'])->name('api.activities.show');
    Route::get('galleries', [GalleryController::class, 'index'])->name('api.galleries.index');
    Route::get('galleries.{gallery}', [GalleryController::class, 'show'])->name('api.galleries.show');
    Route::get('photos', [PhotoController::class, 'index'])->name('api.photos.index');
    Route::get('photos.{photo}', [PhotoController::class, 'show'])->name('api.photos.show');
    Route::get('users', [UserController::class, 'index'])->name('api.users.index');

    // Rutas que requieren autenticación
    Route::middleware('auth:api')->group(function() {
        Route::get('auths.user', [AuthController::class, 'user'])->name('api.auths.user');
        Route::get('auths.logout', [AuthController::class, 'logout'])->name('api.auths.logout');
    });

    // Rutas que requieren autenticación Y permisos de administrador
    Route::middleware(['auth:api', 'admin'])->group(function() {
        Route::post('activities.store', [ActivityController::class, 'store'])->name('api.activities.store');
        Route::put('activities.update.{activity}', [ActivityController::class, 'update'])->name('api.activities.update');
        Route::delete('activities.delete.{activity}', [ActivityController::class, 'destroy'])->name('api.activities.delete');
        Route::post('galleries.store', [GalleryController::class, 'store'])->name('api.galleries.store');
        Route::put('galleries.update.{gallery}', [GalleryController::class, 'update'])->name('api.galleries.update');
        Route::delete('galleries.delete.{gallery}', [GalleryController::class, 'destroy'])->name('api.galleries.delete');
        Route::post('photos.store', [PhotoController::class, 'store'])->name('api.photos.store');
        Route::put('photos.update.{photo}', [PhotoController::class, 'update'])->name('api.photos.update');
        Route::delete('photos.delete.{photo}', [PhotoController::class, 'destroy'])->name('api.photos.delete');
        Route::put('users.update.{user}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('users.delete.{user}', [UserController::class, 'destroy'])->name('api.users.delete');
    });