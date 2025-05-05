<?php

namespace App\Http\Controllers;

use App\Models\MoodStreak;
use App\Models\User;
use Illuminate\Http\Request;

class MoodStreakController extends Controller
{
    /**
     * Display a listing of the mood streaks.
     */
    public function index()
    {
        $moodStreaks = MoodStreak::with('user')->paginate(10);
        return view('mood_streaks.index', compact('moodStreaks'));
    }

    /**
     * Show the form for creating a new mood streak.
     */
    public function create()
    {
        $users = User::all();
        return view('mood_streaks.create', compact('users'));
    }

    /**
     * Store a newly created mood streak in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'streak_count' => 'required|integer|min:0',
        ]);

        MoodStreak::create($validated);

        return redirect()->route('mood-streaks.index')->with('success', 'Mood streak created successfully.');
    }

    /**
     * Display the specified mood streak.
     */
    public function show(MoodStreak $moodStreak)
    {
        return view('mood_streaks.show', compact('moodStreak'));
    }

    /**
     * Show the form for editing the specified mood streak.
     */
    public function edit(MoodStreak $moodStreak)
    {
        $users = User::all();
        return view('mood_streaks.edit', compact('moodStreak', 'users'));
    }

    /**
     * Update the specified mood streak in storage.
     */
    public function update(Request $request, MoodStreak $moodStreak)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'streak_count' => 'required|integer|min:0',
        ]);

        $moodStreak->update($validated);

        return redirect()->route('mood-streaks.index')->with('success', 'Mood streak updated successfully.');
    }

    /**
     * Remove the specified mood streak from storage.
     */
    public function destroy(MoodStreak $moodStreak)
    {
        $moodStreak->delete();

        return redirect()->route('mood-streaks.index')->with('success', 'Mood streak deleted successfully.');
    }
}
