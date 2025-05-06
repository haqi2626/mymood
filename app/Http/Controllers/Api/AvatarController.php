<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avatar;

class AvatarController extends Controller
{
    // Menampilkan daftar semua avatar
    public function index()
    {
        $avatars = Avatar::all()->map(function ($avatar) {
            // Langsung mengonversi path avatar menjadi URL lengkap yang berada di dalam folder public
            $avatar->avatar_path = asset($avatar->avatar_path); // Misalnya, asset('avatars/avatar1.jpg')
            return $avatar;
        });
    
        return response()->json($avatars);
    }

    // Menampilkan data avatar berdasarkan ID
    public function show($id)
    {
        $avatar = Avatar::find($id);

        if ($avatar) {
            // Langsung mengonversi path avatar menjadi URL lengkap
            $avatar->avatar_path = asset($avatar->avatar_path); // Misalnya, asset('avatars/avatar1.jpg')
            return response()->json($avatar);
        }

        return response()->json(['message' => 'Avatar not found'], 404);
    }
}
