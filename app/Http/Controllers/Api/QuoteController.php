<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;

class QuoteController extends Controller
{
        public function index()
    {
        $quotes = Quote::all();

        return response()->json([
            'success' => true,
            'data' => $quotes,
        ]);
    }

    // Ambil 1 quote random (misalnya untuk ditampilkan di homepage)
    public function random()
    {
        $quote = Quote::inRandomOrder()->first();

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada quote ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $quote,
        ]);
    }

}
