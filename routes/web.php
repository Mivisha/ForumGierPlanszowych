<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenreController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::resource('genres', GenreController::class)->only([
    'index',
]);
require __DIR__.'/auth.php';
