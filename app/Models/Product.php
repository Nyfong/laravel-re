<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'stock', 'low_stock_threshold', 'last_import_date'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function stockImports()
    {
        return $this->hasMany(StockImport::class);
    }
    
    public function isLowStock()
    {
        // Current stock check
        if ($this->stock < $this->low_stock_threshold) {
            return true;
        }
        
        // Calculate average daily order rate (last 30 days)
        $days = 30;
        $recentOrders = $this->orders()
            ->where('order_date', '>=', Carbon::now()->subDays($days))
            ->sum('quantity');
        $dailyOrderRate = $recentOrders / $days;
        
        // Estimate days until stockout
        $daysUntilStockout = $this->stock / max($dailyOrderRate, 1); // Avoid division by zero
        
        // Check import duration
        $lastImport = $this->last_import_date ?? Carbon::now()->subDays(30);
        $daysSinceLastImport = Carbon::now()->diffInDays($lastImport);
        
        // Consider stock low if stockout in < 7 days or no import in > 30 days
        return $daysUntilStockout < 7 || $daysSinceLastImport > 30;
    }
}