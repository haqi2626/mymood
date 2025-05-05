<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Search input styles */
        .search-input {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            padding-left: 2.5rem;
            width: 250px;
            background-color: white;
            transition: all 0.2s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .search-container {
            position: relative;
        }

        /* Status badge styles */
        .status-active {
            background-color: #10b981;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }

        .status-inactive {
            background-color: #ef4444;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
    </style>
</head>

<body class="bg-blue-100">
    <!-- Overlay for sidebar -->
    <div class="overlay" id="overlay"></div>

        <!-- Sidebar -->
        @include('components.sidebar')

        <div class="flex-1 p-6">
            <!-- Ini isi konten utama -->
            @yield('content')
        </div>
        </div>
    
    <div class="content-wrapper" id="content-wrapper">
        <div class="container mx-auto p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <!-- Left - Hamburger Menu -->
                <div>
                    <button id="hamburger-menu" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Center - Page Title -->
                <h1 class="text-2xl font-bold text-gray-800">Quotes Management</h1>
                
                <!-- Right - Search Box -->
                <div class="search-container">
                    <span class="search-icon">
                        <i class="fas fa-search"></i>
                    </span>
                    <form action="{{ route('quotes.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Search quotes..." class="search-input" value="{{ request('search') }}">
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <!-- Action Button -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">All Quotes</h2>
                    <a href="{{ route('quotes.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add New Quote
                    </a>
                </div>
                
                @if ($message = Session::get('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ $message }}</p>
                </div>
                @endif
                
                <!-- Quotes Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quote
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Author
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Added
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($quotes as $quote)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $quote->id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    "{{ $quote->quote }}"
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $quote->author }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $quote->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('quotes.edit', $quote->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('quotes.destroy', $quote->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this quote?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No quotes found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $quotes->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Element selectors
            const hamburgerMenu = document.getElementById('hamburger-menu');
            const closeButton = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const contentWrapper = document.getElementById('content-wrapper');
            const menuLinks = document.querySelectorAll('.sidebar-menu-item');
            
            // Open sidebar
            hamburgerMenu.addEventListener('click', function() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                contentWrapper.classList.add('shifted');
            });
            
            // Close sidebar with X button
            closeButton.addEventListener('click', function() {
                closeSidebar();
            });
            
            // Close sidebar with overlay click
            overlay.addEventListener('click', function() {
                closeSidebar();
            });
            
            // Close sidebar with menu item click
            menuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    closeSidebar();
                });
            });

            // Function to close sidebar
            function closeSidebar() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                contentWrapper.classList.remove('shifted');
            }

            // Close sidebar with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
