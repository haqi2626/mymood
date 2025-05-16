<?php
namespace App\Http\Controllers\API;

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

        $perPage = $request->input('per_page', 10);
        $moods = $query->orderBy('date', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

}
