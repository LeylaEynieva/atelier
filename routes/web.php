<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/request', [ContactController::class, 'store'])->name('request.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('services', ServiceController::class);
        Route::resource('materials', MaterialController::class);
        Route::resource('order-statuses', OrderStatusController::class);
        Route::resource('payment-statuses', PaymentStatusController::class);
        Route::get('/users', [AuthController::class, 'index'])->name('users.index');
        Route::delete('/users/{user}', [AuthController::class, 'destroy'])->name('users.destroy');
    });
});