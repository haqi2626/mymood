<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use App\Models\MoodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\MoodStreak;
use Illuminate\Support\Carbon;

class MoodController extends Controller
{
    /**
     * Display a listing of the user's moods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Mood::with('moodType', 'tags')->where('user_id', Auth::id());
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Filter by mood type if provided
        if ($request->has('mood_type_id')) {
            $query->where('mood_type_id', $request->mood_type_id);
        }
        
        // Filter by tag if provided
        if ($request->has('tag_id')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }
        
        // Sort by date (default is descending - newest first)
        $sortDirection = $request->input('sort_direction', 'desc');
        $moods = $query->orderBy('date', $sortDirection)->get();
        
        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

    /**
     * Store a newly created mood in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'mood_type_id' => 'required|exists:mood_types,id',
        'color' => 'nullable|string|max:7',
        'emoji' => 'nullable|string|max:10',
        'note' => 'nullable|string',
        'date' => 'required|date',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }

    $moodData = $request->except('tags');
    $moodData['user_id'] = Auth::id();

    $mood = Mood::create($moodData);

    if ($request->has('tags')) {
        $mood->tags()->attach($request->tags);
    }

    // === MoodStreak logic ===
    $date = Carbon::parse($request->date);
    $user = Auth::user();

    $latestStreak = MoodStreak::where('user_id', $user->id)
        ->orderByDesc('end_date')
        ->first();

    if ($latestStreak && $latestStreak->end_date->copy()->addDay()->isSameDay($date)) {
        // Lanjutkan streak
        $latestStreak->update([
            'end_date' => $date,
            'streak_count' => $latestStreak->streak_count + 1,
        ]);
        $currentStreak = $latestStreak;
    } elseif (!$latestStreak || !$latestStreak->end_date->isSameDay($date)) {
        // Mulai streak baru
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


    /**
     * Update the specified mood in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mood_type_id' => 'exists:mood_types,id',
            'color' => 'nullable|string|max:7',
            'emoji' => 'nullable|string|max:10',
            'note' => 'nullable|string',
            'date' => 'date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
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

        // Sync tags if provided
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

    /**
     * Remove the specified mood from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mood = Mood::where('user_id', Auth::id())->find($id);

        if (!$mood) {
            return response()->json([
                'success' => false,
                'message' => 'Mood not found or does not belong to user'
            ], 404);
        }

        // Detach all tags
        $mood->tags()->detach();
        
        // Delete the mood
        $mood->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mood deleted successfully'
        ]);
    }
    
    /**
     * Get mood statistics for the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStatistics(Request $request)
    {
        $query = Mood::where('user_id', Auth::id());
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Get counts by mood type
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