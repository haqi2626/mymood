<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Form styles */
        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            background-color: white;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* User preview styles */
        .user-preview {
            background-color: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3b82f6;
        }

        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Avatar selection styles */
        .avatar-option {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.2s;
            border: 3px solid transparent;
            background-color: #f3f4f6;
        }
        
        .avatar-option:hover {
            transform: scale(1.1);
        }
        
        .avatar-option.selected {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        
        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 15px;
        }
    </style>
</head>
<body class="bg-blue-100">
    <div class="container mx-auto p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ← Back
            </a>
            <h1 class="text-2xl font-bold text-gray-800 ml-4">Edit User: {{ $user->name }}</h1>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Enter full name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="Enter email address" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Leave empty to keep current password">
                            <p class="text-xs text-gray-500 mt-1">Leave blank if you don't want to change the password</p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Confirm new password">
                        </div>
                        
                        <!-- Avatar Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Avatar</label>
                            <input type="hidden" id="avatar_id" name="avatar_id" value="{{ old('avatar_id', $user->avatar_id) }}">
                            
                            <div class="avatar-grid mt-3">
                                @foreach($avatars as $avatar)
                                    <div class="flex flex-col items-center">
                                        <img 
                                        src="{{ asset($avatar->avatar_path) }}"
                                        alt="Avatar {{ $avatar->id }}" 
                                        class="avatar-option {{ old('avatar_id', $user->avatar_id) == $avatar->id ? 'selected' : '' }}" 
                                        data-avatar-id="{{ $avatar->id }}"
                                        onclick="selectAvatar(this, {{ $avatar->id }})"
                                        >
                                        <span class="text-xs text-gray-500 mt-1">Avatar {{ $avatar->id }}</span>
                                    </div>
                                @endforeach
                                
                                <!-- Default placeholder if no avatar selected -->
                                <div class="flex flex-col items-center">
                                    <img
                                        src="https://ui-avatars.com/api/?name=DE&background=lightgreen" 
                                        alt="Default Avatar"
                                        class="avatar-option {{ old('avatar_id', $user->avatar_id) ? '' : 'selected' }}"
                                        data-avatar-id=""
                                        onclick="selectAvatar(this, '')"
                                    >
                                    <span class="text-xs text-gray-500 mt-1">Default</span>
                                </div>
                            </div>
                            @error('avatar_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role" name="role" class="form-select">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email Verification</label>
                            <div class="mt-2">
                                <div class="flex items-center">
                                    <input id="verify_email" name="verify_email" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ $user->email_verified_at ? 'checked' : '' }}>
                                    <label for="verify_email" class="ml-2 block text-sm text-gray-900">
                                        Mark email as verified
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">User's email was {{ $user->email_verified_at ? 'verified on ' . $user->email_verified_at->format('M d, Y') : 'not verified' }}.</p>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">User Preview</label>
                            <div class="user-preview mt-2">
                                <div class="flex items-center">
                                    <img id="preview-avatar" class="user-avatar" src="{{ $user->avatar_id ? asset($user->avatar->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" alt="User Avatar">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" id="preview-name">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500" id="preview-email">{{ $user->email }}</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span id="preview-role" class="px-2 py-1 text-xs rounded-full {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($user->role) }}</span>
                                            <span id="preview-verified" class="ml-2 px-2 py-1 text-xs rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                @if($user->email_verified_at)
                                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                                @else
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update User</button>
                </div>
            </form>
            
            <!-- Delete Form -->
        
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const roleSelect = document.getElementById('role');
            const verifyEmail = document.getElementById('verify_email');
            const avatarIdInput = document.getElementById('avatar_id');
            
            const previewName = document.getElementById('preview-name');
            const previewEmail = document.getElementById('preview-email');
            const previewRole = document.getElementById('preview-role');
            const previewVerified = document.getElementById('preview-verified');
            const previewAvatar = document.getElementById('preview-avatar');
            
            // Update preview as user types
            nameInput.addEventListener('input', updatePreview);
            emailInput.addEventListener('input', updatePreview);
            roleSelect.addEventListener('change', updatePreview);
            verifyEmail.addEventListener('change', updatePreview);
            
            // Initialize with any selected avatar
            const selectedAvatar = document.querySelector('.avatar-option.selected');
            if (selectedAvatar) {
                previewAvatar.src = selectedAvatar.src;
            }
            
            // Handle broken avatar images
            document.querySelectorAll('.avatar-option').forEach(img => {
                img.addEventListener('error', function() {
                    this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent('Avatar ' + (this.dataset.avatarId || 'Default'))}&background=random`;
                });
            });
            
            function updatePreview() {
                const name = nameInput.value.trim() || '{{ $user->name }}';
                const email = emailInput.value.trim() || '{{ $user->email }}';
                const role = roleSelect.options[roleSelect.selectedIndex].text;
                const isVerified = verifyEmail.checked;
                
                previewName.textContent = name;
                previewEmail.textContent = email;
                previewRole.textContent = role;
                
                // Update verification badge
                if (isVerified) {
                    previewVerified.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Verified';
                    previewVerified.className = 'ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800';
                } else {
                    previewVerified.innerHTML = '<i class="fas fa-clock mr-1"></i> Pending';
                    previewVerified.className = 'ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800';
                }
                
                // Update role badge color
                if (role.toLowerCase() === 'admin') {
                    previewRole.className = 'px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800';
                } else {
                    previewRole.className = 'px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800';
                }
            }
            
            // Initialize preview
            updatePreview();
        });
        
        // Avatar selection function
        function selectAvatar(element, avatarId) {
            // Remove selected class from all avatars
            document.querySelectorAll('.avatar-option').forEach(avatar => {
                avatar.classList.remove('selected');
            });
            
            // Add selected class to clicked avatar
            element.classList.add('selected');
            
            // Update hidden input value
            document.getElementById('avatar_id').value = avatarId;
            
            // Update preview avatar
            document.getElementById('preview-avatar').src = element.src;
        }
    </script>
</body>
</html>