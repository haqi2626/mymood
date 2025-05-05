<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use App\Models\User;
use App\Models\MoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mood::with('user');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('mood_type', 'like', "%{$search}%")
                ->orWhere('note', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $moods = $query->latest()->paginate(10);
        
        return view('moods.index', compact('moods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $moodTypes = MoodType::all();
        
        return view('moods.create', compact('users', 'moodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'mood_type' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'emoji' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Mood::create($validator->validated());


        return redirect()->route('moods.index')
            ->with('success', 'Mood created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mood $mood)
    {
        return view('moods.show', compact('mood'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mood $mood)
    {
        $users = User::all();
        $moodTypes = MoodType::all();
        
        return view('moods.edit', compact('mood', 'users', 'moodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mood $mood)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'mood_type' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'emoji' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // Update mood yang sudah ada
        $mood->update($validator->validated());
    
        return redirect()->route('moods.index')
            ->with('success', 'Mood updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mood $mood)
    {
        $mood->delete();

        return redirect()->route('moods.index')
            ->with('success', 'Mood deleted successfully.');
    }
    
    /**
     * Get predefined mood types with their emojis and colors.
     */
    // private function getMoodTypes()
    // {
    //     return [
    //         ['type' => 'Happy', 'emoji' => '😊', 'color' => '#FFD700'],
    //         ['type' => 'Sad', 'emoji' => '😢', 'color' => '#6495ED'],
    //         ['type' => 'Exxcited', 'emoji' => '🎉', 'color' => '#FF4500'],
    //         ['type' => 'Calm', 'emoji' => '😌', 'color' => '#98FB98'],
    //         ['type' => 'Angry', 'emoji' => '😠', 'color' => '#FF6347'],
    //         ['type' => 'Anxious', 'emoji' => '😰', 'color' => '#DDA0DD'],
    //     ];
    // }
}
