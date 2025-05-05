<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .user-avatar {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .info-card {
            background-color: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-blue-100">
    <div class="container mx-auto p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ← Back
            </a>
            <h1 class="text-2xl font-bold text-gray-800 ml-4">User Details</h1>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex flex-col md:flex-row">
                <!-- User Avatar and Basic Info -->
                <div class="md:w-1/3 flex flex-col items-center p-4">
                    <div class="relative mb-4">
                        
                        @if($user->avatar_id)
                            <img class="user-avatar" src="{{ asset($user->avatar->avatar_path) }}" 
                                alt="{{ $user->name }}" 
                                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=128'">
                            <div class="avatar-badge" title="Custom Avatar">
                                <i class="fas fa-image text-xs"></i>
                            </div>
                        @else
                            <img class="user-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=128" alt="{{ $user->name }}">
                            <div class="avatar-badge bg-gray-500" title="Default Avatar">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <div class="mt-2 flex flex-wrap justify-center">
                        <span class="px-2 py-1 text-xs rounded-full m-1 {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($user->role) }}</span>
                        <span class="px-2 py-1 text-xs rounded-full m-1 {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            @else
                                <i class="fas fa-clock mr-1"></i> Not Verified
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- User Details -->
                <div class="md:w-2/3 p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">User Information</h3>
                    
                    <div class="info-card mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">User ID</p>
                                <p class="text-md font-medium">{{ $user->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Joined Date</p>
                                <p class="text-md font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="text-md font-medium">{{ $user->updated_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Verification</p>
                                <p class="text-md font-medium">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-yellow-600">
                                            <i class="fas fa-clock mr-1"></i> Not verified
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity</h3>
                    <div class="info-card">
                        <p class="text-gray-600 text-sm">Recent activity will be displayed here.</p>
                        <!-- This section can be expanded to show user activity logs, etc. -->
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 mr-2">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
