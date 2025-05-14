<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use App\Models\MoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\MoodStreak;
use Carbon\Carbon;

class MoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Mood::with('moodType', 'tags')->where('user_id', Auth::id());
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        if ($request->has('mood_type_id')) {
            $query->where('mood_type_id', $request->mood_type_id);
        }

        if ($request->has('tag_id')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }
        
        $sortDirection = $request->input('sort_direction', 'desc');
        $moods = $query->orderBy('date', $sortDirection)->get();

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mood_type_id' => 'required|exists:mood_types,id',
            'color' => 'nullable|string|max:7',
            'emoji' => 'nullable|string|max:10',
            'note' => 'nullable|string',
            'date' => 'required|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $date = Carbon::parse($request->date);

        // Prevent duplicate mood entry for the same date
        $existingMood = Mood::where('user_id', $user->id)->whereDate('date', $date)->first();
        if ($existingMood) {
            return response()->json([
                'success' => false,
                'message' => 'Mood for this date already exists'
            ], 409);
        }

        // Create new mood
        $moodData = $request->except('tags');
        $moodData['user_id'] = $user->id;
        $mood = Mood::create($moodData);

        if ($request->has('tags')) {
            $mood->tags()->attach($request->tags);
        }

        // === MoodStreak logic ===
        $latestStreak = MoodStreak::where('user_id', $user->id)
            ->orderByDesc('end_date')
            ->first();

        $today = Carbon::today();

        // Ensure end_date is a Carbon instance
        if ($latestStreak && $latestStreak->end_date instanceof Carbon) {
            $endDate = $latestStreak->end_date;

            // Check if the date is consecutive to the latest streak
            if ($endDate->copy()->addDay()->isSameDay($date)) {
                // Continue the streak
                $newCount = $latestStreak->streak_count + 1;
                $latestStreak->update([
                    'end_date' => $date,
                    'streak_count' => $newCount,
                ]);
                $currentStreak = $latestStreak;
            } elseif ($date->gt($endDate)) {
                // Start a new streak
                $currentStreak = MoodStreak::create([
                    'user_id' => $user->id,
                    'start_date' => $date,
                    'end_date' => $date,
                    'streak_count' => 1,
                ]);
            } else {
                $currentStreak = null; // No streak update as the date is not in sequence
            }
        } else {
            // No streak exists, start a new streak
            $currentStreak = MoodStreak::create([
                'user_id' => $user->id,
                'start_date' => $date,
                'end_date' => $date,
                'streak_count' => 1,
            ]);
        }

        $mood->load('moodType', 'tags');

        return response()->json([
            'success' => true,
            'message' => 'Mood created successfully',
            'data' => [
                'mood' => $mood,
                'streak' => $currentStreak
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mood_type_id' => 'nullable|exists:mood_types,id',
            'color' => 'nullable|string|max:7',
            'emoji' => 'nullable|string|max:10',
            'note' => 'nullable|string',
            'date' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $mood = Mood::where('user_id', Auth::id())->find($id);
        if (!$mood) {
            return response()->json([
                'success' => false,
                'message' => 'Mood not found or does not belong to user'
            ], 404);
        }

        // Update the mood
        $mood->update($request->except('tags'));

        if ($request->has('tags')) {
            $mood->tags()->sync($request->tags);
        }

        // Load relationships for response
        $mood->load('moodType', 'tags');

        return response()->json([
            'success' => true,
            'message' => 'Mood updated successfully',
            'data' => $mood
        ]);
    }

    public function destroy($id)
    {
        $mood = Mood::where('user_id', Auth::id())->find($id);
        if (!$mood) {
            return response()->json([
                'success' => false,
                'message' => 'Mood not found or does not belong to user'
            ], 404);
        }

        $mood->tags()->detach();
        $mood->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mood deleted successfully'
        ]);
    }

    public function getStatistics(Request $request)
    {
        $query = Mood::where('user_id', Auth::id());

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $moodTypeCounts = $query->get()
            ->groupBy('mood_type_id')
            ->map(function ($items, $key) {
                $moodType = MoodType::find($key);
                return [
                    'mood_type_id' => $key,
                    'mood_type_name' => $moodType ? $moodType->name : 'Unknown',
                    'count' => count($items)
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'total_moods' => $query->count(),
                'mood_types' => $moodTypeCounts
            ]
        ]);
    }
}
