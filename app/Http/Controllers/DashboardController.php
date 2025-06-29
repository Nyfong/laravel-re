<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;
use App\Models\StockImport;
use App\Models\StockRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function admin(Request $request)
    {
        $totalOrders = Order::count();
        $totalLowStock = Product::whereRaw('stock < low_stock_threshold')
            ->orWhereHas('orders', function ($q) {
                $q->where('order_date', '>=', now()->subDays(30));
            })->count();

        $productsQuery = Product::with('category');
        
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $productsQuery->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $products = $productsQuery->paginate(10);
        return view('dashboard.admin', compact('totalOrders', 'totalLowStock', 'products'));
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
        })->with(['category', 'orders' => function ($q) {
            $q->latest()->take(5);
        }]);
        
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

    public function nonPurchasedProducts(Request $request)
    {
        $query = Product::doesntHave('orders')->with('category');
        
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        $nonPurchasedProducts = $query->paginate(10);
        return view('dashboard.non-purchased-products', compact('nonPurchasedProducts'));
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

        Log::info('Updating order status', [
            'order_id' => $order->id,
            'current_status' => $order->status,
            'new_status' => $request->status,
            'current_quantity' => $order->quantity,
            'new_quantity' => $request->quantity,
            'product_id' => $order->product_id,
            'current_stock' => $order->product ? $order->product->stock : 'N/A',
        ]);

        try {
            return DB::transaction(function () use ($request, $order) {
                $product = $order->product;
                if (!$product) {
                    Log::error('Product not found for order', ['order_id' => $order->id]);
                    return back()->withErrors(['error' => 'Product not found for this order']);
                }

                $newStatus = $request->status;
                $newQuantity = $request->quantity;

                if ($newStatus === 'Shipped' && $order->status !== 'Shipped') {
                    if ($product->stock < $newQuantity) {
                        Log::warning('Insufficient stock', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'available_stock' => $product->stock,
                            'requested_quantity' => $newQuantity,
                        ]);
                        return back()->withErrors(['error' => 'Insufficient stock for ' . $product->name . '. Available: ' . $product->stock]);
                    }
                    $product->decrement('stock', $newQuantity);
                    Log::info('Stock decremented', [
                        'product_id' => $product->id,
                        'decremented_by' => $newQuantity,
                        'new_stock' => $product->stock - $newQuantity,
                    ]);
                } elseif ($order->status === 'Shipped' && $newStatus !== 'Shipped') {
                    $product->increment('stock', $order->quantity);
                    Log::info('Stock restored', [
                        'product_id' => $product->id,
                        'incremented_by' => $order->quantity,
                        'new_stock' => $product->stock + $order->quantity,
                    ]);
                } elseif ($order->status === 'Shipped' && $newStatus === 'Shipped' && $newQuantity !== $order->quantity) {
                    $stockDifference = $order->quantity - $newQuantity;
                    if ($stockDifference < 0 && $product->stock < abs($stockDifference)) {
                        Log::warning('Insufficient stock for quantity update', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'available_stock' => $product->stock,
                            'requested_additional' => abs($stockDifference),
                        ]);
                        return back()->withErrors(['error' => 'Insufficient stock for ' . $product->name . '. Available: ' . $product->stock]);
                    }
                    $product->increment('stock', $stockDifference);
                    Log::info('Stock adjusted', [
                        'product_id' => $product->id,
                        'adjusted_by' => $stockDifference,
                        'new_stock' => $product->stock + $stockDifference,
                    ]);
                }

                $order->update([
                    'status' => $newStatus,
                    'quantity' => $newQuantity,
                    'order_date' => $request->order_date,
                ]);

                Log::info('Order updated successfully', [
                    'order_id' => $order->id,
                    'new_status' => $newStatus,
                    'new_quantity' => $newQuantity,
                    'new_stock' => $product->stock,
                ]);

                return back()->with('success', 'Order updated successfully');
            });
        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => 'Failed to update order: ' . $e->getMessage()]);
        }
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

    public function requestStock(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        StockRequest::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'requested_by' => Auth::id(),
        ]);

        return back()->with('success', 'Stock request submitted successfully');
    }

    public function confirmStockRequest(Request $request, StockRequest $stockRequest)
    {
        if (Auth::user()->role !== 'warehouse') {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $request->validate([
            'status' => 'required|in:confirmed,rejected',
        ]);

        $stockRequest->update(['status' => $request->status]);

        if ($request->status === 'confirmed') {
            $product = $stockRequest->product;
            $product->update([
                'stock' => $product->stock + $stockRequest->quantity,
                'last_import_date' => now(),
            ]);
        }

        return back()->with('success', 'Stock request ' . $request->status . ' successfully');
    }

    public function exportOrders()
    {
        return Excel::download(new OrdersExport, 'orders_' . now()->format('Ymd_His') . '.xlsx');
    }
}