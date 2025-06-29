<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockImport extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'import_date'];
}