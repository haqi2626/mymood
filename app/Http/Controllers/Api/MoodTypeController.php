<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodType;

class MoodTypeController extends Controller
{
    public function index()
    {
        $moodTypes = MoodType::all()->map(function ($mood) {
            // Pastikan image_url mengarah ke public path
            $mood->image_url = asset($mood->image_url);
            return $mood;
        });
    
        return response()->json(['data' => $moodTypes]);
    }
    
    
    
}