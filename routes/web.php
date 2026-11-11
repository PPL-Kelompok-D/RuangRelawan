<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Community\ActivityController as CommunityActivityController;

// Sisipkan di dalam group middleware role:community
Route::middleware(['auth', 'role:community'])->group(function () {
    Route::get('/community/my-activities', [CommunityActivityController::class, 'myActivities'])->name('community.my-activities');
    Route::get('/community/activities', fn () => redirect()->route('community.my-activities'))->name('community.activities.index');
    Route::get('/community/activities/create', [CommunityActivityController::class, 'create'])->name('community.activities.create');
    Route::post('/community/activities', [CommunityActivityController::class, 'store'])->name('community.activities.store');
    Route::get('/community/activities/{id}/edit', [CommunityActivityController::class, 'edit'])->whereNumber('id')->name('community.activities.edit');
    Route::put('/community/activities/{id}', [CommunityActivityController::class, 'update'])->whereNumber('id')->name('community.activities.update');
});
