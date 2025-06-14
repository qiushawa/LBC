<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'product_id'; // 假設主鍵為 product_id
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

    // 獲取所有上架產品 (參數: 產品類別ID[可選])
    public function scopeByCategory($query, $categoryId = null)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'product_id', 'product_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'product_id');
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class, 'product_id', 'product_id');
    }
}
