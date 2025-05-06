<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoodStreak extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'start_date', 'end_date', 'streak_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function moods()
    {
        return $this->hasMany(Mood::class, 'user_id', 'user_id')
            ->whereColumn('date', '>=', 'mood_streaks.start_date')
            ->whereColumn('date', '<=', 'mood_streaks.end_date')
            ->with('moodType');
    }

}
