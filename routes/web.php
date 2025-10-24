<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    $user = User::query()
        ->where('name', 'Sonny Feil')
        ->first();
    dd($user);
});
