<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ActivityController;
    use App\Http\Controllers\GalleryController;
    use App\Http\Controllers\PhotoController;
    use App\Http\Controllers\UserController;



    // Ruta para obtener usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');

    // Rutas públicas de autenticación
    Route::post('auth.login', [AuthController::class, 'login'])->name('api.login');
    Route::post('auth.register', [AuthController::class, 'register'])->name('api.register');

    // Rutas públicas para consultar
    Route::get('activities', [ActivityController::class, 'index'])->name('api.activities.index');
    Route::get('activities/{activity}', [ActivityController::class, 'show'])->name('api.activity.show');
    Route::get('galleries', [GalleryController::class, 'index'])->name('api.galleries.index');
    Route::get('galleries/{gallery}', [GalleryController::class, 'show'])->name('api.gallery.show');
    Route::get('photos', [PhotoController::class, 'index'])->name('api.photos.index');
    Route::get('photos/{photo}', [PhotoController::class, 'show'])->name('api.photo.show');
    Route::get('users', [UserController::class, 'index'])->name('api.users.index');

    // Rutas que requieren autenticación
    Route::middleware('auth:api')->group(function() {
        Route::get('auth.me', [AuthController::class, 'me'])->name('api.auth.me');
        Route::get('auth.logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });

    // Rutas que requieren autenticación Y permisos de administrador
    Route::middleware(['auth:api', 'admin'])->group(function() {
        Route::post('activity/store', [ActivityController::class, 'store'])->name('api.activity.store');
        Route::put('activity/update/{activity}', [ActivityController::class, 'update'])->name('api.activity.update');
        Route::delete('activity/delete/{activity}', [ActivityController::class, 'destroy'])->name('api.activity.delete');
        Route::post('gallery/store', [GalleryController::class, 'store'])->name('api.gallery.store');
        Route::put('gallery/update/{gallery}', [GalleryController::class, 'update'])->name('api.gallery.update');
        Route::delete('gallery/delete/{gallery}', [GalleryController::class, 'destroy'])->name('api.gallery.delete');
        Route::post('photo/store', [PhotoController::class, 'store'])->name('api.photo.store');
        Route::put('photo/update/{photo}', [PhotoController::class, 'update'])->name('api.photo.update');
        Route::delete('photo/delete/{photo}', [PhotoController::class, 'destroy'])->name('api.photo.delete');
        Route::put('user/apdate/{user}', [UserController::class, 'update'])->name('api.user.update');
        Route::delete('user/delete/{user}', [UserController::class, 'destroy'])->name('api.user.delete');
    });