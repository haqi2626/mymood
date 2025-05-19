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

        $authUser = Auth::user();

        if (!$authUser || !($authUser instanceof User)) {
            return response()->json(['message' => 'Unauthorized or invalid user'], 401);
        }

        $authUser->avatar_id = $request->avatar_id;
        $authUser->save();

        // Ambil ulang user dari database untuk memastikan instance Eloquent
        $user = User::with('avatar')->find($authUser->id);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'user' => $this->formatUserData($user),
        ]);
    }

    public function getProfile()
    {
        $authUser = Auth::user();

        if (!$authUser || !($authUser instanceof User)) {
            return response()->json(['message' => 'Unauthorized or invalid user'], 401);
        }

        $user = User::with('avatar')->find($authUser->id);

        return response()->json([
            'user' => $this->formatUserData($user),
        ]);
    }

    private function formatUserData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_id' => $user->avatar_id,
            'avatar' => $user->avatar ? [
                'id' => $user->avatar->id,
                'avatar_path' => url($user->avatar->avatar_path),
            ] : null,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
