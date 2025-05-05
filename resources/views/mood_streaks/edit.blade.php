@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Mood Streak</h1>
        
        <form action="{{ route('mood-streaks.update', $moodStreak->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $moodStreak->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $moodStreak->start_date }}" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $moodStreak->end_date }}">
            </div>
            <div class="form-group">
                <label for="streak_count">Streak Count</label>
                <input type="number" name="streak_count" id="streak_count" class="form-control" value="{{ $moodStreak->streak_count }}" required>
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
@endsection
