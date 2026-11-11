<?php

use App\Http\Controllers\Community\CommunityApplicantController as CommunityApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:community'])->group(function () {
    Route::get('/community/activities/{id}/applicants', [CommunityApplicationController::class, 'index'])->whereNumber('id')->name('community.activities.applicants.index');
    Route::post('/community/applications/{id}/accept', [CommunityApplicationController::class, 'accept'])->whereNumber('id')->name('community.applications.accept');
    Route::post('/community/applications/{id}/reject', [CommunityApplicationController::class, 'reject'])->whereNumber('id')->name('community.applications.reject');
});
