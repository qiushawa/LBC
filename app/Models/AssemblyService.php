<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssemblyService extends Model
{
    protected $primaryKey = 'service_id';
    public $timestamps = false;
    protected $table = 'assembly_services';
    protected $fillable = [
        "service_name",
        "service_description",
        "service_fee",
        "availability_status",
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'service_id', 'service_id');
    }

    public function configurations()
    {
        return $this->hasMany(CustomConfiguration::class, 'service_id', 'service_id');
    }
}
