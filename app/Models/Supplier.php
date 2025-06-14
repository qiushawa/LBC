<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Supplier extends Model
{
    use HasFactory;
    protected $table = 'Suppliers';
    public $timestamps = false;
    protected $primaryKey = 'supplier_id'; // 假設主鍵為 supplier_id
    protected $fillable = [
        'supplier_name',
        'contact_phone',
        'contact_address',
    ];
}
