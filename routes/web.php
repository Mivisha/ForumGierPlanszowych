<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Users\UserTable;
use App\Livewire\BoardGames\BoardGameTable;
use App\Livewire\Genres\GenreTable;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('/users', UserTable::class)
        ->name('users.index');
    
    Route::get('/board-games', BoardGameTable::class)
        ->middleware('can:boardgame_access')
        ->name('board-games.index');
    Route::get('/board-games/create', [App\Http\Controllers\BoardGameController::class, 'create'])->name('board-games.create');
    Route::post('/board-games', [App\Http\Controllers\BoardGameController::class, 'store'])->name('board-games.store');
    Route::get('/board-games/{boardGame}/edit', [App\Http\Controllers\BoardGameController::class, 'edit'])->name('board-games.edit');
    Route::put('/board-games/{boardGame}', [App\Http\Controllers\BoardGameController::class, 'update'])->name('board-games.update');
    Route::delete('/board-games/{boardGame}', [App\Http\Controllers\BoardGameController::class, 'destroy'])->name('board-games.destroy');

    Route::get('/genres', GenreTable::class)
        ->middleware('can:genre_access')
        ->name('genres.index');
    Route::get('/genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('/genres', [GenreController::class, 'store'])->name('genres.store');
    Route::get('/genres/{genre}/edit', [GenreController::class, 'edit'])->name('genres.edit');
    Route::put('/genres/{genre}', [GenreController::class, 'update'])->name('genres.update');
    Route::delete('/genres/{genre}', [GenreController::class, 'destroy'])->name('genres.destroy');

    Route::get('/posts/create', [App\Http\Controllers\PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [App\Http\Controllers\PostController::class, 'store'])->name('posts.store');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::middleware('auth')->group(function () {
        Route::post('/favorites/{boardGame}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    });
    // Route::resource('genres', GenreTable::class)->only([
    //     'index',
    //     'create',
    //     'store',
    // ]);

    //Route::resource('users', UserController::class)->only(['index',]);
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
