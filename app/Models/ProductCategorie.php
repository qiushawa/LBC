<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProductCategorie extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $fillable = [
        'category_name',
        'category_icon',
    ];

    // 關聯到這個產品類別的產品
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
