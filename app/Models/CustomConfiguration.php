<?php
// app/Models/CustomConfiguration.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomConfiguration extends Model
{
    protected $table = 'custom_configurations';
    protected $primaryKey = 'config_id';

    public $timestamps = false; // Assuming no timestamps are used
    public $incrementing = true;
    protected $fillable = ['user_id', 'total_price', 'order_id', 'service_id', 'config_name'];

    public function details()
    {
        return $this->hasMany(ConfigurationDetail::class, 'config_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function assemblyService()
    {
        return $this->belongsTo(AssemblyService::class, 'service_id', 'service_id');
    }
}
