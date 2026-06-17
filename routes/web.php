<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::view('/', 'auth.login')->name('login');
Route::post('/login', [LoginController::class, 'login']) ->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('budget')->group(function () {
        Route::view('/dashboard', 'budget.dashboard')->name('budget.dashboard');
        Route::view('/logbook', 'budget.logbook')->name('budget.logbook');
    });

    Route::prefix('accounting')->group(function () {
        Route::view('/dashboard', 'accounting.dashboard')->name('accounting.dashboard');
        Route::view('/logbook', 'accounting.logbook')->name('accounting.logbook');
        Route::view('/quarterly-summary', 'accounting.quarterly-summary')->name('accounting.quarterly-summary');
        Route::view('/cashier-status', 'accounting.cashier-status')->name('accounting.cashier-status');
    });

    Route::prefix('admin')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
        Route::view('/users', 'admin.users')->name('admin.users');
    });
});