<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Discount extends Model
{
    // use HasFactory;
    protected $table = 'discounts';
    public $timestamps = false;
    protected $fillable = [
        "discount_name",
        "discount_description",
        "discount_type",
        "discount_value",
        "start_date",
        "end_date",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'discount_id');
    }
}
