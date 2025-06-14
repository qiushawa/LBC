<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $primaryKey = 'inventory_id';
    protected $table = 'inventory';
    public $timestamps = false; // Assuming no created_at/updated_at columns

    protected $fillable = [
        'product_id',
        'stock_quantity',
        'low_stock_threshold',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
