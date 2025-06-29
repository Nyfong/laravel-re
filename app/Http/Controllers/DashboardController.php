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
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $lowStockProducts = $query->get();
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
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $lowStockProducts = $query->paginate(10);
        return view('dashboard.warehouse', compact('lowStockProducts'));
    }

    public function delivery(Request $request)
    {
        $query = Order::latest()->with('product');
        
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('CAST(id AS TEXT) ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('product', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $orders = $query->paginate(10);
        return view('dashboard.delivery', compact('orders'));
    }

    public function lowStockProducts(Request $request)
    {
        $query = Product::where(function ($q) {
            return $q->whereRaw('stock < low_stock_threshold')
                     ->orWhereHas('orders', function ($q) {
                         $q->where('order_date', '>=', now()->subDays(30));
                     });
        })->with('category');
        
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $lowStockProducts = $query->paginate(10);
        return view('dashboard.lowstock-products', compact('lowStockProducts'));
    }

    public function purchaseHistory(Request $request)
    {
        $query = Order::latest()->with('product');
        
        if ($request->has('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('CAST(id AS TEXT) ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('product', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $orders = $query->paginate(10);
        return view('dashboard.purchase-history', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'delivery') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }
        
        $request->validate([
            'status' => 'required|in:Pending,Shipped,Delivered',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'required|date|before_or_equal:today',
        ]);
        
        $order->update([
            'status' => $request->status,
            'quantity' => $request->quantity,
            'order_date' => $request->order_date,
        ]);
        
        return back()->with('success', 'Order updated successfully');
    }

    public function logStockImport(Request $request)
    {
        if (Auth::user()->role !== 'warehouse') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'import_date' => 'required|date',
        ]);
        
        StockImport::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'import_date' => $request->import_date,
        ]);
        
        $product = Product::find($request->product_id);
        $product->update([
            'stock' => $product->stock + $request->quantity,
            'last_import_date' => $request->import_date,
        ]);
        
        return back()->with('success', 'Stock import logged');
    }

    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'orders_' . now()->format('Ymd_His') . '.xlsx');
    }
}