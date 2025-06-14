<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $primaryKey = 'setting_id';
    protected $fillable = ['user_id', 'preferred_language', 'theme', 'display_currency'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
