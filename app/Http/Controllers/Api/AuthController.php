<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        //check user
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        //check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password is not match'
            ], 404);
        }

        //generate token
        $token = $user->createToken('token')->plainTextToken;

        $user->load('avatar');

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }


    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successfully',
        ]);
    }
    //register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar_id' => $request->avatar_id, 
        ]);

        $user->load('avatar');

        return response()->json([
            'message' => 'User berhasil terdaftar',
            'user' => $user
        ], 201);
    }

    //get user
    public function me(Request $request)
    {
    return response()->json([
        'message' => 'User yang sedang login',
        'user' => $request->user()
    ]);
    }
}
