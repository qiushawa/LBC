<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'employee_id';
    public $incrementing = true;
    protected $guarded = [];
    public $timestamps = false; // 禁用時間戳

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'permission_id');
    }
}
