<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/fix-assets', function() {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    return "Cache cleared! Try your homepage now.";
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/expense-tracker', 'expense-tracker')
    ->name('expense-tracker');

Route::view('/calendar', 'calendar')
    ->middleware(['auth'])
    ->name('calendar');

Route::get('/api/expenses-by-date', function () {
    $date = request('date');
    if (! $date) {
        return response()->json(['error' => 'Date is required'], 400);
    }

    $expenses = \App\Models\Expense::where('user_id', auth()->id())
        ->whereDate('created_at', $date)
        ->get(['id', 'description', 'amount', 'category', 'created_at']);

    $total = $expenses->sum('amount');

    return response()->json([
        'date' => $date,
        'expenses' => $expenses,
        'total' => $total,
    ]);
})->middleware(['auth'])->name('api.expenses-by-date');

require __DIR__.'/settings.php';
