<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar_id' => 'required|exists:avatars,id',
        ]);

        $user = User::findOrFail($id);
        $user->avatar_id = $request->avatar_id;
        $user->save();

        $user->load('avatar');

        return response()->json([
            'message' => 'Avatar updated successfully',
            'user' => $user
        ]);
    }
}
