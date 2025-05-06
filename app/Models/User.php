<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ Tambahkan ini

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_id',
        'role',
        'email_verified_at'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Di dalam model User.php
    public function scopeOnlyUsers($query)
    {
        return $query->where('role', 'user');
    }


    public function themes()
    {
        return $this->hasMany(Theme::class);
    }

    public function moods()
    {
        return $this->hasMany(\App\Models\Mood::class);
    }

    public function moodStreaks()
    {
        return $this->hasMany(MoodStreak::class);
    }

    public function tags()
    {
        return $this->hasMany(\App\Models\Tag::class);
    }

    public function avatar()
    {
        return $this->belongsTo(Avatar::class);
    }
    


}
