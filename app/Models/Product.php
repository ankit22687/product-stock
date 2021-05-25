<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','code','description'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function scopeOrderByStockQuantity($query, $order = 'desc')
    {
        return $query->orderBy('stock_quantity', $order);
    }

    public function scopeProductsWithStockOnly($query)
    {
        return $query->havingRaw('stock_quantity > 0');
    }
}
