<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Customer: cek status cucian milik akun login.
    Route::get('/status-laundry', [TransactionController::class, 'statusLaundry']);

    // Admin: kelola master data, transaksi, dan laporan.
    Route::middleware('can:admin')->group(function () {
        Route::get('/services', [ServiceController::class, 'index']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
        Route::put('/customers/{customer}', [CustomerController::class, 'update']);
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

        Route::get('/transactions/summary', [TransactionController::class, 'summary']);
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
        Route::put('/transactions/{id}/status', [TransactionController::class, 'updateStatus']);
        Route::put('/transactions/{id}/payment', [TransactionController::class, 'updatePayment']);
        Route::post('/transactions/{id}/payment', [TransactionController::class, 'updatePayment']);
        Route::post('/transactions/{id}/condition-photo', [TransactionController::class, 'updateConditionPhoto']);
        

        Route::get('/reports/stats', [ReportController::class, 'stats']);
    });
});
