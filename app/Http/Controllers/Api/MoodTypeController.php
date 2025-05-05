<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodType;

class MoodTypeController extends Controller
{
    public function index()
    {
        return MoodType::all();
    }
}