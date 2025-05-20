<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use Illuminate\Http\Request;

class PublicMoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Mood::with(['user', 'moodType', 'tags'])
            ->where('is_public', true);

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan kata kunci pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 🔍 Filter berdasarkan nama mood type
        if ($request->has('mood_type') && !empty($request->mood_type)) {
            $query->whereHas('moodType', function ($q) use ($request) {
                $q->where('name', $request->mood_type);
            });
        }

        // 🔍 Atau filter berdasarkan ID mood_type
        if ($request->has('mood_type_id')) {
            $query->where('mood_type_id', $request->mood_type_id);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['date', 'user_name', 'mood_type'];

        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'user_name') {
                $query->join('users', 'moods.user_id', '=', 'users.id')
                      ->orderBy('users.name', $sortOrder)
                      ->select('moods.*');
            } elseif ($sortBy === 'mood_type') {
                $query->join('mood_types', 'moods.mood_type_id', '=', 'mood_types.id')
                      ->orderBy('mood_types.name', $sortOrder)
                      ->select('moods.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('date', 'desc');
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $moods = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }
}
