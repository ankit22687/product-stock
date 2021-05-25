<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['production_date'];

    protected $fillable = ['product_id','on_hand','taken','production_date'];

    protected $table = 'stock';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
