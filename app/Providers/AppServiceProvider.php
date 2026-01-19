<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Genre;
use App\Policies\GenrePolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\BoardGame;  
use App\Policies\BoardGamePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //zapytanie ktore loguje wszystkie zapytania do bazy danych
        // DB::listen(function ($query){
        //     Log::info(
        //         $query->sql,
        //         $query->bindings,
        //         $query->time
        //     );
        // });
    }

    protected $policies = [
        User::class => UserPolicy::class,
        Genre::class => GenrePolicy::class,
        BoardGame::class => BoardGamePolicy::class,
    ];
}
