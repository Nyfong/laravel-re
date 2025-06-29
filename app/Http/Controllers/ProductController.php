<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search by product name or category
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereHas('category', function ($q) use ($searchTerm) {
                      $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }
        
        // Filter by category
        if ($request->has('category_id') && $request->category_id !== 'All') {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->paginate(10);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }
}