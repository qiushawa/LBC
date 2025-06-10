<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;
    protected $fillable = [
        'permission_level', // 權限等級
        'job_title', // 職稱
    ];

    // 關聯到這個權限的員工
    public function employees()
    {
        return $this->hasMany(Employee::class, 'permission_id');
    }
}
