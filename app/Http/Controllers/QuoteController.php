<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotes = Quote::latest()->paginate(10);
        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quotes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'required',
        ]);

        Quote::create($request->all());

        return redirect()->route('quotes.index')
            ->with('success', 'Quote created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        return view('quotes.edit', compact('quote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        $request->validate([
            'quote' => 'required',
            'author' => 'required',
        ]);

        $quote->update($request->all());

        return redirect()->route('quotes.index')
            ->with('success', 'Quote updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Quote deleted successfully');
    }
}
