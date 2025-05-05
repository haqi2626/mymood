<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mood</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #e6f0ff;
            color: #333;
        }
        .container {
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            margin-bottom: 20px;
        }
        .back-button {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        .card-header {
            margin-bottom: 30px;
        }
        .card-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-control:focus {
            outline: none;
            border-color: #4285f4;
        }
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px 12px;
        }
        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            min-height: 120px;
            resize: vertical;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            font-size: 16px;
            border: none;
        }
        .btn-primary {
            background-color: #4285f4;
            color: white;
        }
        .btn-secondary {
            background-color: #f1f3f4;
            color: #333;
            margin-right: 10px;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .mood-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        .color-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-6">
        <div class="header">
            <a href="{{ route('moods.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1 class="page-title">Mood Management</h1>
            <div></div> <!-- Empty div for flex spacing -->
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Edit Mood</h2>
                <p>Update mood entry details</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('moods.update', $mood) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $mood->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mood_type" class="form-label">Mood Type</label>
                    <select name="mood_type" id="mood_type" class="form-select @error('mood_type') is-invalid @enderror">
                        <option value="">Select Mood Type</option>
                        @foreach($moodTypes as $type)
                            <option value="{{ $type->type }}" {{ old('mood_type', $mood->mood_type ?? '') == $type->type ? 'selected' : '' }}>
                                {{ $type->emoji }} {{ $type->type }}
                            </option>
                        @endforeach
                    </select>
                    @error('mood_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div id="mood-preview" class="mood-preview" style="{{ $mood->mood_type ? '' : 'display: none;' }}">
                        <div id="preview-color" class="color-circle" style="background-color: {{ $mood->color }};"></div>
                        <span id="preview-emoji">{{ $mood->emoji }}</span>
                        <span id="preview-type">{{ $mood->mood_type }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $mood->date ? $mood->date->format('Y-m-d') : '') }}">
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="note" class="form-label">Note</label>
                    <textarea name="note" id="note" class="form-textarea @error('note') is-invalid @enderror">{{ old('note', $mood->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="emoji" id="emoji" value="{{ old('emoji', $mood->emoji) }}">
                <input type="hidden" name="color" id="color" value="{{ old('color', $mood->color) }}">

                <div class="form-actions">
                    <a href="{{ route('moods.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Mood</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moodTypeSelect = document.getElementById('mood_type');
            const emojiInput = document.getElementById('emoji');
            const colorInput = document.getElementById('color');
            const moodPreview = document.getElementById('mood-preview');
            const previewColor = document.getElementById('preview-color');
            const previewEmoji = document.getElementById('preview-emoji');
            const previewType = document.getElementById('preview-type');

            // Initialize preview if a mood type is already selected
            if (moodTypeSelect.value) {
                updateMoodPreview();
            }

            moodTypeSelect.addEventListener('change', function() {
                updateMoodPreview();
            });

            function updateMoodPreview() {
                const selectedOption = moodTypeSelect.options[moodTypeSelect.selectedIndex];
                if (selectedOption.value) {
                    const emoji = selectedOption.getAttribute('data-emoji');
                    const color = selectedOption.getAttribute('data-color');
                    const type = selectedOption.value;
                    
                    emojiInput.value = emoji;
                    colorInput.value = color;
                    
                    previewColor.style.backgroundColor = color;
                    previewEmoji.textContent = emoji;
                    previewType.textContent = type;
                    moodPreview.style.display = 'flex';
                } else {
                    emojiInput.value = '';
                    colorInput.value = '#000000';
                    moodPreview.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
