<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    public $incrementing = true;
    protected $fillable = ['discount_name', 'discount_type', 'discount_value', 'start_date', 'end_date', 'discount_description'];
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_product_mappings', 'discount_id', 'product_id');
    }
}
