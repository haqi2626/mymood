<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodType extends Model
{
    protected $fillable = [
        'name', 
        'image_url'
    ];

        public function moods()
    {
        return $this->hasMany(Mood::class);
    }
}
