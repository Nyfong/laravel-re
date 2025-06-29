<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/warehouse', [DashboardController::class, 'warehouse'])->name('dashboard.warehouse');
    Route::get('/dashboard/delivery', [DashboardController::class, 'delivery'])->name('dashboard.delivery');
    Route::get('/orders/export', [DashboardController::class, 'exportOrders'])->name('orders.export');
});