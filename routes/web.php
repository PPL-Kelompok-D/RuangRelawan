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
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-activities', [\App\Http\Controllers\User\ApplicationController::class, 'myActivities'])->name('user.my-activities');
});
