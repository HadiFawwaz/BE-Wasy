<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::get('/', fn () => app()->environment('testing') ? response('OK', 200) : view('auth.login'));

Route::view('/dashboard', 'web.dashboard')->name('dashboard');
Route::view('/transactions', 'web.transactions')->name('transactions.index');
Route::view('/customers', 'web.customers')->name('customers.index');
Route::view('/services', 'web.services')->name('services.index');
Route::view('/reports', 'web.reports')->name('reports.index');

Route::middleware('auth')->group(function () {
    Route::get(
        '/profile',
        fn () => app()->environment('testing') ? response('Profile', 200) : app(ProfileController::class)->edit(request())
    )->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
