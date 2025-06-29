<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'stock', 'low_stock_threshold', 'last_import_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isLowStock()
    {
        if ($this->stock < $this->low_stock_threshold) {
            return true;
        }
        $days = 30;
        $recentOrders = $this->orders()
            ->where('order_date', '>=', now()->subDays($days))
            ->sum('quantity');
        $dailyOrderRate = $recentOrders / $days;
        $daysUntilStockout = $this->stock / max($dailyOrderRate, 1);
        $lastImport = $this->last_import_date ?? now()->subDays(30);
        $daysSinceLastImport = now()->diffInDays($lastImport);
        return $daysUntilStockout < 7 || $daysSinceLastImport > 30;
    }
}