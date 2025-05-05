<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = ['avatar_path'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
