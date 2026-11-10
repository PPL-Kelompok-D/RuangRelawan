<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// US-01: Pengelolaan Profil Relawan (Thalita)
// ==========================================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.destroy-avatar');
    Route::delete('/profile/avatar-alt', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});

// ==========================================
// US-09: Melihat Status Pendaftaran (Thalita)
// ==========================================
use App\Http\Controllers\User\ApplicationController as UserApplicationController;

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-activities', [UserApplicationController::class, 'myActivities'])->name('user.my-activities');
    Route::post('/activities/{id}/apply', [UserApplicationController::class, 'store'])->whereNumber('id')->name('activities.apply');
});
