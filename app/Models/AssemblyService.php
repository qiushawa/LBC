<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssemblyService extends Model
{
    protected $primaryKey = 'service_id';
    public $timestamps = true;
    protected $table = 'assembly_services';
    protected $fillable = [
        "service_name",
        "service_description",
        "service_fee",
        "availability_status",
    ];

    public function assemblyService()
    {
        return $this->belongsTo(AssemblyService::class, 'service_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
