<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/warehouse', [DashboardController::class, 'warehouse'])->name('dashboard.warehouse');
    Route::get('/dashboard/delivery', [DashboardController::class, 'delivery'])->name('dashboard.delivery');
    Route::get('/dashboard/lowstock-products', [DashboardController::class, 'lowStockProducts'])->name('dashboard.lowstock-products');
    Route::get('/dashboard/purchase-history', [DashboardController::class, 'purchaseHistory'])->name('dashboard.purchase-history');
    Route::post('/order/{order}/status', [DashboardController::class, 'updateOrderStatus'])->name('order.updateStatus');
    Route::post('/stock/import', [DashboardController::class, 'logStockImport'])->name('stock.import');
    Route::get('/orders/export', [DashboardController::class, 'exportOrders'])->name('orders.export');
});