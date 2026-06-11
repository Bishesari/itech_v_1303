<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome')->name('home');
Route::middleware('auth')->group(function () {
    Route::livewire('/select-role', 'pages::auth.select-role')->name('select-role');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
