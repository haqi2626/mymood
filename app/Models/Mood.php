<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mood extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'mood_type_id', 
        'color', 
        'emoji', 
        'note', 
        'date',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'mood_tag');
    }

    public function moodType()
    {
        return $this->belongsTo(MoodType::class);
    }

}
