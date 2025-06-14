<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $primaryKey = 'detail_id';
    public $timestamps = false;
    protected $table = 'order_details';
    protected $fillable = ['order_id', 'product_id', 'discount_id', 'quantity', 'unit_price', 'discount_amount', 'subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_id');
    }
}
