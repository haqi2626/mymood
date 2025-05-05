@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mood Streak Details</h1>
        
        <table class="table">
            <tr>
                <th>User</th>
                <td>{{ $moodStreak->user->name }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ $moodStreak->start_date }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ $moodStreak->end_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Streak Count</th>
                <td>{{ $moodStreak->streak_count }}</td>
            </tr>
        </table>
        
        <a href="{{ route('mood-streaks.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
