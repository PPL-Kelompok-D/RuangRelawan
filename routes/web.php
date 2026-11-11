<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityVerificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin/verifications')
    ->name('admin.verifications.')
    ->controller(ActivityVerificationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/reject', 'reject')->name('reject');
    });
