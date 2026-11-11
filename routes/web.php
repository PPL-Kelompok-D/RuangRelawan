<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityProfileController;
use App\Http\Controllers\User\ApplicationController;

// US-02: Community Profile
Route::get('/', [CommunityProfileController::class, 'edit'])
    ->withoutMiddleware(['auth', 'role:community'])
    ->name('home');

Route::prefix('community')
    ->name('community.')
    ->controller(CommunityProfileController::class)
    ->group(function () {
        Route::get('/profile', 'edit')
            ->withoutMiddleware(['auth', 'role:community'])
            ->name('profile.edit');

        Route::put('/profile', 'update')
            ->withoutMiddleware(['auth', 'role:community'])
            ->name('profile.update');
    });

// US-08: Pendaftaran Aktivitas
Route::prefix('activities')
    ->name('activities.')
    ->controller(ApplicationController::class)
    ->group(function () {
        Route::post('/{id}/apply', 'store')
            ->whereNumber('id')
            ->name('apply');
    });

Route::get('/my-activities', [ApplicationController::class, 'myActivities'])
    ->name('user.my-activities');

// Login dan Register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.perform');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.perform');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');