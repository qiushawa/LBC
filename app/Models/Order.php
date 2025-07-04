<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
    public $timestamps = true;
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'order_date',
        'order_status',
        'total_amount',
        'shipping_fee',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'payment_method',
        'payment_status',
        'service_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
    public function assemblyService()
    {
        return $this->belongsTo(AssemblyService::class, 'service_id', 'service_id');
    }
}
