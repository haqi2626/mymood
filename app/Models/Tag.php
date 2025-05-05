<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'color'];

    public function moods()
    {
        return $this->belongsToMany(Mood::class, 'mood_tag');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
