<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\StockImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $query = Product::where(function ($q) {
            return $q->whereRaw('stock < low_stock_threshold')
                     ->orWhereHas('orders', function ($q) {
                         $q->where('order_date', '>=', now()->subDays(30));
                     });
        })->with('category');
        
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $lowStockProducts = $query->paginate(10);
        $totalOrders = Order::count();
        $totalLowStock = $query->count();
        
        return view('dashboard.admin', compact('totalOrders', 'totalLowStock', 'lowStockProducts'));
    }

    public function warehouse(Request $request)
    {
        $query = Product::where(function ($q) {
            return $q->whereRaw('stock < low_stock_threshold')
                     ->orWhereHas('orders', function ($q) {
                         $q->where('order_date', '>=', now()->subDays(30));
                     });
        })->with('category');
        
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $lowStockProducts = $query->paginate(10);
        return view('dashboard.warehouse', compact('lowStockProducts'));
    }

    public function delivery(Request $request)
    {
        $query = Order::latest()->with('product');
        
        // Status filtering
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        // Search by product name or order ID
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $orders = $query->paginate(10);
        return view('dashboard.delivery', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'delivery') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }
        
        $request->validate([
            'status' => 'required|in:Pending,Shipped,Delivered'
        ]);
        
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated');
    }

    public function logStockImport(Request $request)
    {
        if (Auth::user()->role !== 'warehouse') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'import_date' => 'required|date'
        ]);
        
        StockImport::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'import_date' => $request->import_date
        ]);
        
        $product = Product::find($request->product_id);
        $product->update([
            'stock' => $product->stock + $request->quantity,
            'last_import_date' => $request->import_date
        ]);
        
        return back()->with('success', 'Stock import logged');
    }

    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'orders_' . now()->format('Ymd_His') . '.xlsx');
    }
}