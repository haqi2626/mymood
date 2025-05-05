<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoodTag extends Model
{
        

    protected $fillable = ['mood_id', 'tag_id'];

    public $timestamps = false;
}

