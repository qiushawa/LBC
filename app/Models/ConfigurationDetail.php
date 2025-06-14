<?php

// app/Models/ConfigurationDetail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigurationDetail extends Model
{
    protected $table = 'configuration_details';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = ['config_id', 'product_id', 'quantity', 'unit_price', 'discount_amount', 'subtotal'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function configuration()
    {
        return $this->belongsTo(CustomConfiguration::class, 'config_id');
    }

}
