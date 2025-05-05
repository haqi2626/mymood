<?php

// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
// use Illuminate\Http\Request;
// use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Auth;

// class AuthenticatedSessionController extends Controller
// {
//     /**
//      * Handle an incoming authentication request.
//      */
//     public function store(LoginRequest $request): Response
//     {
//         $request->authenticate();

//         $request->session()->regenerate();

//         return response()->noContent();
//     }

//     /**
//      * Destroy an authenticated session.
//      */
//     public function destroy(Request $request): Response
//     {
//         Auth::guard('web')->logout();

//         $request->session()->invalidate();

//         $request->session()->regenerateToken();

//         return response()->noContent();
//     }
// }


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // Autentikasi dan regenerasi session
        $request->authenticate();
        $request->session()->regenerate();

        // Membuat token untuk user
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        // Mengembalikan response JSON dengan data user dan token
        return response()->json([
            'message' => 'Login successful',
            'user' => $request->user(),
            'token' => $token,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    // public function destroy(Request $request): JsonResponse
    // {
    //     // Logout dan invalidasi session
    //     Auth::guard('web')->logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     // Mengembalikan response JSON dengan pesan logout berhasil
    //     return response()->json([
    //         'message' => 'Logged out successfully',
    //     ]);
    // }

    public function destroy(Request $request): JsonResponse
    {
        // Hapus token akses yang digunakan oleh user yang terautentikasi
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
    
        // Mengembalikan response JSON dengan pesan logout berhasil
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
    

}
