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
    Route::get('/dashboard/lowstock-purchases', [DashboardController::class, 'lowstockPurchases'])->name('dashboard.lowstock-purchases');
    Route::post('/order/{order}/status', [DashboardController::class, 'updateOrderStatus'])->name('order.updateStatus');
    Route::post('/stock/import', [DashboardController::class, 'logStockImport'])->name('stock.import');
    Route::get('/orders/export', [DashboardController::class, 'exportOrders'])->name('orders.export');
});