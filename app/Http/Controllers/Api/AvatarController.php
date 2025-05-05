<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avatar;

class AvatarController extends Controller
{
    public function index()
    {
        $avatars = Avatar::all()->map(function ($avatar) {
            $avatar->avatar_path = asset($avatar->avatar_path);
            return $avatar;
        });
    
        return response()->json($avatars);
    }
}
