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

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

            if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }
        
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['date', 'user_name', 'mood_type'];

            if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'user_name') {
                // Sort berdasarkan user name via relasi user
                $query->join('users', 'moods.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortOrder)
                    ->select('moods.*');
            } elseif ($sortBy === 'mood_type') {
                // Sort berdasarkan nama mood type (asumsi mood_type table punya kolom 'name')
                $query->join('mood_types', 'moods.mood_type_id', '=', 'mood_types.id')
                    ->orderBy('mood_types.name', $sortOrder)
                    ->select('moods.*');
            } else {
                // Sort by date atau kolom langsung di moods
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            // default sorting jika kolom tidak valid
            $query->orderBy('date', 'desc');
        }


        $perPage = $request->input('per_page', 10);
        $moods = $query->orderBy('date', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

}
