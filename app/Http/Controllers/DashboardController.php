<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalOrders = Order::count();
        $lowStockProducts = Product::whereRaw('stock < low_stock_threshold')->get();
        $totalLowStock = $lowStockProducts->count();
        
        return view('dashboard.admin', compact('totalOrders', 'totalLowStock', 'lowStockProducts'));
    }

    public function warehouse()
    {
        $lowStockProducts = Product::whereRaw('stock < low_stock_threshold')->get();
        return view('dashboard.warehouse', compact('lowStockProducts'));
    }

    public function delivery()
    {
        $orders = Order::latest()->take(50)->get();
        return view('dashboard.delivery', compact('orders'));
    }

    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'orders_' . now()->format('Ymd_His') . '.xlsx');
    }
}