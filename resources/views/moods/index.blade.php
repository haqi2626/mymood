<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        .search-container {
            position: relative;
        }
        .search-input {
            padding: 10px 15px 10px 40px;
            border: none;
            border-radius: 8px;
            width: 300px;
            font-size: 16px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 24px;
            font-weight: 600;
        }
        .add-button {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            color: #666;
            font-weight: 500;
            background-color: #f9f9f9;
        }
        td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .edit-button {
            color: #4285f4;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        .delete-button {
            color: #f44336;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }
        .mood-display {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .color-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 5px;
            background-color: white;
            color: #333;
            text-decoration: none;
        }
        .pagination span.current {
            background-color: #4285f4;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <!-- Left - Hamburger Menu -->
            <div class="flex items-center mb-6">
                <a href="{{ route('app') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ← Back
                </a>
            </div>
            
            <!-- Center - Page Title -->
            <h1 class="text-2xl font-bold text-gray-800">Quotes Management</h1>
            
            <!-- Right - Search Box -->
            <div class="search-container">
                <span class="search-icon">
                    <i class="fas fa-search"></i>
                </span>
                <form action="{{ route('moods.index') }}" method="GET">
                    <input type="text" name="search" placeholder="Search quotes..." class="search-input" value="{{ request('search') }}">
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">All Moods</h2>
                <a href="{{ route('moods.create') }}" class="add-button">
                    <i class="fas fa-plus"></i> Add New Mood
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>USER</th>
                        <th>MOOD</th>
                        <th>NOTE</th>
                        <th>DATE ADDED</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($moods as $mood)
                        <tr>
                            <td>{{ $mood->id }}</td>
                            <td>{{ $mood->user->name ?? 'N/A' }}</td>
                            <td>
                                <div class="mood-display">
                                    <div class="color-circle" style="background-color: {{ $mood->color }};"></div>
                                    <span>{{ $mood->emoji }}</span>
                                    <span>{{ $mood->mood_type }}</span>
                                </div>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($mood->note, 50) }}</td>
                            <td>{{ $mood->date ? $mood->date->format('M d, Y') : 'N/A' }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('moods.edit', $mood) }}" class="edit-button">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('moods.destroy', $mood) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this mood?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No mood entries found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $moods->links() }}
            </div>
        </div>
    </div>
</body>
</html>
