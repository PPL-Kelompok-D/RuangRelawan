<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ActivityExplorationController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/activities', [ActivityExplorationController::class, 'index'])
    ->name('activities.index');

    Route::get('/activities/{id}', [ActivityExplorationController::class, 'show'])
    ->whereNumber('id')
    ->name('activities.show');
