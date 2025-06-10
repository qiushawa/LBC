<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable = [
        "category_id",
        "supplier_id",
        "product_name",
        "product_description",
        "product_image",
        "product_price",
        "launch_date",
        "launch_status",
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategorie::class, 'category_id');
    }
}
