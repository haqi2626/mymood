<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'theme_name', 'primary_color', 'secondary_color', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
