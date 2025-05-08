<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodType;

class MoodTypeController extends Controller
{
    public function index()
    {
        $moods = MoodType::all();
    
        foreach ($moods as $mood) {
            $mood->image_url = asset(str_replace('public/', '', $mood->image_url));
        }
    
        return response()->json([
            'data' => $moods
        ]);
    }
    
    
}