<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;
    protected $table = 'ads';
    protected $primaryKey = 'ad_id';
    public $timestamps = false;
    protected $fillable = [
        'ad_title',
        'ad_content',
        'ad_banner',
    ];
    public function getBannerUrlAttribute(): ?string
    {
        return $this->ad_banner ? asset('images/ads/' . $this->ad_banner.'.png') : null;
    }
}
