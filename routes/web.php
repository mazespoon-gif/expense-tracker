<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/expense-tracker', 'expense-tracker')
    ->name('expense-tracker');

require __DIR__.'/settings.php';
