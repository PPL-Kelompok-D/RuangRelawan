<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ActivityExplorationController;
use App\Http\Controllers\User\ApplicationController as UserApplicationController;
use Illuminate\Support\Facades\Route;

// ==========================================
// US-12: Detail Aktivitas & Tautan WhatsApp (Thalita)
// ==========================================
Route::get('/', [ActivityExplorationController::class, 'index'])->name('home');
Route::get('/activities', [ActivityExplorationController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [ActivityExplorationController::class, 'show'])->whereNumber('id')->name('activities.show');
Route::get('/activities/{id}/completion/document', [ActivityExplorationController::class, 'completionDocument'])->whereNumber('id')->name('activities.completion.document');

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
    Route::get('/my-activities', [UserApplicationController::class, 'myActivities'])->name('user.my-activities');
    Route::post('/activities/{id}/apply', [UserApplicationController::class, 'store'])->whereNumber('id')->name('activities.apply');
});
