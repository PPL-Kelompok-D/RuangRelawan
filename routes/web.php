<?php

use App\Http\Controllers\Admin\ActivityVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Community\ActivityController as CommunityActivityController;
use App\Http\Controllers\Community\CommunityApplicantController as CommunityApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ActivityExplorationController;
use App\Http\Controllers\User\ApplicationController as UserApplicationController;
use Illuminate\Support\Facades\Route;

// ==========================================
// Halaman Utama & Eksplorasi Aktivitas (Nabila - US-06 & US-07)
// ==========================================
Route::get('/', [ActivityExplorationController::class, 'index'])->name('home');
Route::get('/activities', [ActivityExplorationController::class, 'index'])->name('activities.index');
Route::get('/activities/{id}', [ActivityExplorationController::class, 'show'])->whereNumber('id')->name('activities.show');

// ==========================================
// Autentikasi (Login & Register)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// US-01, US-08, & US-09: Relawan / User (Thalita & Keisha)
// ==========================================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.destroy-avatar');
    Route::delete('/profile/avatar-alt', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::post('/activities/{id}/apply', [UserApplicationController::class, 'store'])->whereNumber('id')->name('activities.apply');
    Route::get('/my-activities', [UserApplicationController::class, 'myActivities'])->name('user.my-activities');
});

// ==========================================
// US-03, US-10, & US-11: Komunitas (Fiona & Fatasya)
// ==========================================
Route::middleware(['auth', 'role:community'])->group(function () {
    Route::get('/community/my-activities', [CommunityActivityController::class, 'myActivities'])->name('community.my-activities');
    Route::get('/community/activities', fn () => redirect()->route('community.my-activities'))->name('community.activities.index');
    Route::get('/community/activities/create', [CommunityActivityController::class, 'create'])->name('community.activities.create');
    Route::post('/community/activities', [CommunityActivityController::class, 'store'])->name('community.activities.store');
    Route::get('/community/activities/{id}/edit', [CommunityActivityController::class, 'edit'])->whereNumber('id')->name('community.activities.edit');
    Route::put('/community/activities/{id}', [CommunityActivityController::class, 'update'])->whereNumber('id')->name('community.activities.update');

    // Manajemen Pendaftar Relawan (Fatasya - US-10 & US-11)
    Route::get('/community/activities/{id}/applicants', [CommunityApplicationController::class, 'index'])->whereNumber('id')->name('community.activities.applicants.index');
    Route::post('/community/applications/{id}/accept', [CommunityApplicationController::class, 'accept'])->whereNumber('id')->name('community.applications.accept');
    Route::post('/community/applications/{id}/reject', [CommunityApplicationController::class, 'reject'])->whereNumber('id')->name('community.applications.reject');
});

Route::get('/community/applicants', fn () => redirect()->route('community.my-activities'))
    ->middleware(['auth', 'role:community'])
    ->name('community.applicants.index');

// ==========================================
// US-04: Verifikasi Aktivitas Admin (Nayla)
// ==========================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/verifications')
    ->name('admin.verifications.')
    ->controller(ActivityVerificationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->whereNumber('id')->name('show');
        Route::post('/{id}/approve', 'approve')->whereNumber('id')->name('approve');
        Route::post('/{id}/reject', 'reject')->whereNumber('id')->name('reject');
    });
