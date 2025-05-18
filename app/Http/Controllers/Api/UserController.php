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

        if (!$user || !($user instanceof User)) {
            return response()->json(['message' => 'Unauthorized or invalid user'], 401);
        }

        $user->avatar_id = $request->avatar_id;
        $user->save();

        return response()->json([
            'message' => 'Avatar updated successfully',
            'user' => $user,
        ]);
    }
}
