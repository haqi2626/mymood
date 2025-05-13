<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoodStreak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoodStreakController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $streaks = MoodStreak::with(['moods.moodType'])
                    ->where('user_id', $user->id)
                    ->get();

        return response()->json(['success' => true, 'data' => $streaks]);
    }

    public function show($id)
    {
        $streak = MoodStreak::with(['moods' => function($query) {
            $query->with('moodType');
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $streak
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'streak_count' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        $streak = MoodStreak::create([
            'user_id' => $user->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'streak_count' => $validated['streak_count'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $streak,
            'message' => 'Mood streak berhasil dibuat!'
        ]);
    }

    // kalau perlu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'streak_count' => 'required|integer|min:0',
        ]);

        $streak = MoodStreak::where('user_id', Auth::id())->findOrFail($id);
        $streak->update($validated);

        return response()->json(['success' => true, 'data' => $streak]);
    }

    public function destroy($id)
    {
        $streak = MoodStreak::where('user_id', Auth::id())->findOrFail($id);
        $streak->delete();

        return response()->json(['success' => true, 'message' => 'Mood streak berhasil dihapus.']);
    }

}
