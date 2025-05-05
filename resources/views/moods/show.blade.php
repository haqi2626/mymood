<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .card-title {
            font-size: 24px;
            font-weight: 600;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            font-size: 16px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .detail-label {
            width: 150px;
            font-weight: 500;
            color: #666;
        }
        .detail-value {
            flex: 1;
        }
        .mood-display {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-circle {
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }
        .note-card {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .note-header {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('moods.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h1 class="page-title">Mood Management</h1>
            <div></div> <!-- Empty div for flex spacing -->
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Mood Details</h2>
                <div class="action-buttons">
                    <a href="{{ route('moods.edit', $mood) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('moods.destroy', $mood) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this mood?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">ID</div>
                <div class="detail-value">{{ $mood->id }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">User</div>
                <div class="detail-value">{{ $mood->user->name ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Mood Type</div>
                <div class="detail-value">
                    <div class="mood-display">
                        <div class="color-circle" style="background-color: {{ $mood->color }};"></div>
                        <span>{{ $mood->emoji }}</span>
                        <span>{{ $mood->mood_type }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Date</div>
                <div class="detail-value">{{ $mood->date ? $mood->date->format('F d, Y') : 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Created At</div>
                <div class="detail-value">{{ $mood->created_at->format('F d, Y H:i:s') }}</div>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $mood->updated_at->format('F d, Y H:i:s') }}</div>
            </div>

            <div class="note-card">
                <div class="note-header">Note</div>
                <div>{{ $mood->note ?? 'No note available' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
