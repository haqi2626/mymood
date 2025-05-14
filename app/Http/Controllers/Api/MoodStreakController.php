<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use App\Models\MoodStreak;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class MoodStreakController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mood_type_id' => 'required|exists:mood_types,id',
            'note' => 'nullable|string', // Validasi untuk note
            'tags' => 'nullable|array', // Validasi untuk tags
            'tags.*' => 'exists:tags,id', // Validasi untuk tag ID
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah user sudah isi mood hari ini
        $existingMood = Mood::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingMood) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengisi mood untuk hari ini.',
            ], 409); // Conflict
        }

        // Simpan mood hari ini
        $moodData = [
            'user_id' => $user->id,
            'mood_type_id' => $request->mood_type_id,
            'date' => $today,
            'note' => $request->note, // Menyimpan note
        ];
        
        $mood = Mood::create($moodData);

        // Menyimpan tags jika ada
        if ($request->has('tags')) {
            $mood->tags()->attach($request->tags);
        }

        // Mood Streak Logic
        $latestStreak = MoodStreak::where('user_id', $user->id)
            ->orderByDesc('end_date')
            ->first();

        $message = null;

        if ($latestStreak && $latestStreak->end_date->copy()->addDay()->isSameDay($today)) {
            // Lanjutkan streak
            $newCount = $latestStreak->streak_count + 1;
            $latestStreak->update([
                'end_date' => $today,
                'streak_count' => $newCount,
            ]);

            // Pesan motivasi
            if (in_array($newCount, [3, 5, 7, 14, 30])) {
                $message = "Keren! Anda sudah mengisi mood selama $newCount hari berturut-turut 🎉";
            }
        } else {
            // Buat streak baru
            MoodStreak::create([
                'user_id' => $user->id,
                'start_date' => $today,
                'end_date' => $today,
                'streak_count' => 1,
            ]);

            $message = "Semangat! Mood streak baru dimulai hari ini 😊";
        }

        return response()->json([
            'success' => true,
            'data' => $mood,
            'message' => $message,
        ]);
    }

    // (Opsional) Tampilkan semua mood milik user
    public function index()
    {
        $user = Auth::user();
        $moods = Mood::with('moodType', 'tags') // Mengambil tags bersama moodType
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $moods
        ]);
    }

    // (Opsional) Detail satu mood
    public function show($id)
    {
        $mood = Mood::with('moodType', 'tags') // Mengambil tags bersama moodType
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $mood
        ]);
    }
}
