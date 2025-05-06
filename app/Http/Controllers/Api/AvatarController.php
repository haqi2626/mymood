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
        // Ambil semua data avatar dari database
        $avatars = Avatar::all();

        // Jika data ada, modifikasi path avatar menggunakan asset() untuk menghasilkan URL lengkap
        $avatars = $avatars->map(function ($avatar) {
            // Pastikan avatar_path yang ada di database mengarah ke lokasi yang benar
            $avatar->avatar_path = asset('avatars/' . $avatar->avatar_path);
            return $avatar;
        });

        // Kembalikan respons dalam format JSON
        return response()->json($avatars);
    }

    // Menampilkan data avatar berdasarkan ID
    public function show($id)
    {
        // Cari avatar berdasarkan ID
        $avatar = Avatar::find($id);

        // Jika avatar ditemukan, modifikasi path dan kembalikan dalam format JSON
        if ($avatar) {
            $avatar->avatar_path = asset('avatars/' . $avatar->avatar_path);
            return response()->json($avatar);
        }

        // Jika tidak ditemukan, kembalikan respons 404
        return response()->json(['message' => 'Avatar not found'], 404);
    }
}
