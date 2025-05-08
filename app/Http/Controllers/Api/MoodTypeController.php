<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodType;

class MoodTypeController extends Controller
{
    public function index()
    {
        $moods = MoodType::all();
    
        // Tambahkan URL lengkap ke image_url
        $moods->transform(function ($item) {
            $item->image_url = asset(str_replace('public/', 'storage/', $item->image_url));
            return $item;
        });
    
        return response()->json([
            'data' => $moods
        ]);
    }
    
}