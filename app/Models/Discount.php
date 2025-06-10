<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Discount extends Model
{
    // use HasFactory;
    protected $table = 'discounts';
    public $timestamps = false;
    protected $fillable = [
        "discount_name",
        "discount_description",
        "discount_type",
        "discount_value",
        "start_date",
        "end_date",
    ];
}
