<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\MoodType;

class MoodTypeController extends Controller
{
    public function index()
    {
        $images = [];
        $directory = public_path('emojies'); // Menunjuk ke folder public/emojies
    
        // Ambil semua file gambar dalam folder
        foreach (File::files($directory) as $file) {
            $images[] = 'https://mymood.mymood.my.id/emojies/' . $file->getFilename();
        }
    
        return response()->json(['data' => $images]);
    }
    
    
}