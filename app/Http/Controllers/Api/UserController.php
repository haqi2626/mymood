<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
public function updateProfile(Request $request)
{
    $authUser = Auth::user();

    if (!$authUser || !($authUser instanceof User)) {
        return response()->json(['message' => 'Unauthorized or invalid user'], 401);
    }

    // Validasi hanya jika dikirim
    $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255|unique:users,email,' . $authUser->id,
        'avatar_id' => 'nullable|exists:avatars,id',
    ]);

    // Update hanya yang dikirim
    if ($request->filled('name')) $authUser->name = $request->name;
    if ($request->filled('email')) $authUser->email = $request->email;
    if ($request->filled('avatar_id')) $authUser->avatar_id = $request->avatar_id;

    $authUser->save();

    $user = User::with('avatar')->find($authUser->id);

    return response()->json([
        'message' => 'Profile updated successfully',
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
