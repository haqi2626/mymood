<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar_id' => 'required|exists:avatars,id',
    ]);

    $user = Auth::user();

    if (!$user || !($user instanceof \App\Models\User)) {
        return response()->json(['message' => 'Unauthorized or invalid user'], 401);
    }

    $user->avatar_id = $request->avatar_id;
    $user->save();

    // Load relasi avatar agar bisa dimasukkan ke respons
    $user->load('avatar');

    return response()->json([
        'message' => 'Avatar updated successfully',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_id' => $user->avatar_id,
            'avatar' => $user->avatar ? [
                'id' => $user->avatar->id,
                'avatar_path' => $user->avatar->avatar_path,
            ] : null,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ],
    ]);
}

}
