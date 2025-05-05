@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Mood Streak</h1>
        
        <form action="{{ route('mood-streaks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="streak_count">Streak Count</label>
                <input type="number" name="streak_count" id="streak_count" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@endsection
