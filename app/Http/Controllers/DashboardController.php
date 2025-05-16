<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $quotes = Quote::latest()->paginate(10);

        // Menghitung total jumlah quotes
        $totalQuotes = Quote::count();

        $totalUsers = User::onlyUsers()->count();
    
        // Mengirim quotes dan totalQuotes ke view
        return view('app', compact('quotes', 'totalQuotes', 'totalUsers'));
        
    }

    
}
