<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
/* Quote container styles */
.quote-container {
  width: 100%;
  max-width: 500px;
  margin: 0 auto;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  overflow: hidden;
  position: relative;
  height: 500px;
}

.quote-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1rem;
  text-align: center;
}

.quotes-wrapper {
  position: relative;
  height: 450px;
  overflow: hidden;
}

.quote-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
  padding: 0.75rem;
  border-radius: 0.375rem;
  background-color: #f9f9f9;
  position: absolute;
  width: calc(100% - 1.5rem);
  animation: moveUpLoop 20s infinite linear;
  opacity: 0;
}

.quote-icon {
  background-color: #f3f4f6;
  border-radius: 50%;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.quote-text {
  font-style: italic;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

@keyframes moveUpLoop {
  0% {
    transform: translateY(300px);
    opacity: 0;
  }
  5% {
    transform: translateY(240px);
    opacity: 1;
  }
  20% {
    transform: translateY(180px);
    opacity: 1;
  }
  35% {
    transform: translateY(120px);
    opacity: 1;
  }
  50% {
    transform: translateY(60px);
    opacity: 1;
  }
  65% {
    transform: translateY(0);
    opacity: 1;
  }
  80% {
    transform: translateY(-60px);
    opacity: 1;
  }
  95% {
    transform: translateY(-120px);
    opacity: 0;
  }
  100% {
    transform: translateY(-180px);
    opacity: 0;
  }
}

.quote-item:nth-child(1) {
  animation-delay: 0s;
}

.quote-item:nth-child(2) {
  animation-delay: 4s;
}

.quote-item:nth-child(3) {
  animation-delay: 8s;
}

.quote-item:nth-child(4) {
  animation-delay: 12s;
}

.quote-item:nth-child(5) {
  animation-delay: 16s;
}

/* Improved Sidebar Styles */


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

/* Calendar Styles */
.calendar-container {
  width: 100%;
  background-color: white;
  border-radius: 0.5rem;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.calendar-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
}

.calendar-nav {
  display: flex;
  gap: 0.5rem;
}

.calendar-nav-btn {
  background: none;
  border: none;
  cursor: pointer;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color 0.2s;
}

.calendar-nav-btn:hover {
  background-color: #f3f4f6;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 0.25rem;
}

.calendar-day-name {
  text-align: center;
  font-weight: 600;
  padding: 0.5rem;
  font-size: 0.875rem;
}

.calendar-day {
  position: relative;
  height: 3.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 0.375rem;
  transition: background-color 0.2s;
}

.calendar-day:hover {
  background-color: #f3f4f6;
}

.calendar-day.selected {
  background-color: #e6f2ff;
}

.calendar-day.today {
  font-weight: bold;
}

.calendar-day.empty {
  cursor: default;
}

.calendar-popup {
  position: absolute;
  top: 0;
  left: 0;
  transform: translateY(-100%);
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0.375rem;
  padding: 0.75rem;
  width: 12rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 10;
  display: none;
}

.calendar-popup.active {
  display: block;
}

.calendar-popup-date {
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.calendar-popup-info {
  font-size: 0.875rem;
  color: #4b5563;
}

    </style>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-blue-100">
    {{-- @include('components.loading') --}}
    <!-- Overlay for sidebar -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    @include('components.sidebar')

    <div class="flex-1 p-6">
        <!-- Ini isi konten utama -->
        @yield('content')
    </div>
    <div class="content-wrapper" id="content-wrapper">
        <div class="container mx-auto p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
              <div>
                <button id="hamburger-menu" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

                <h1 class="text-2xl font-bold text-center">Dashboard</h1>
                
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('users.index') }}" >
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center mb-2 cursor-pointer">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <div>Total Users</div>
                    </div>
                    <div class="text-3xl font-bold">{{ number_format($totalUsers) }}</div>
                </div></a>

                <a  href="{{ route('quotes.index') }}">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center mb-2 cursor-pointer">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-quote-right text-blue-500"></i>
                        </div>
                        <div>Quotes</div>
                    </div>
                    <div class="text-3xl font-bold">{{ number_format($totalQuotes) }}</div>
                </div>
                </a>
                <a  href="{{ route('moods.index') }}">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-chart-line text-blue-500"></i>
                        </div>
                        <div>Bounce Rate</div>
                    </div>
                    <div class="text-3xl font-bold">27.5%</div>
                </div>
            </a>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center mb-2">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-dollar-sign text-blue-500"></i>
                        </div>
                        <div>Revenue</div>
                    </div>
                    <div class="text-3xl font-bold">$45,678</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                <!-- Calendar (replacing Line Chart) -->
                <div class="bg-white rounded-lg shadow p-6 col-span-2">
                    <h2 class="text-lg font-semibold mb-4 quote-title">Kalender Interaktif</h2>
                    <div id="calendar" class="calendar-container"></div>
                </div>

                <!-- Quotes -->
                <div class="quote-container">
                    <h2 class="quote-title">Quotes</h2>
                    <div class="quotes-wrapper">
                        @foreach($quotes as $index => $quote)
                        <div class="quote-item" style="animation-delay: 0s;">
                            <div class="quote-icon">
                                <i class="fas fa-quote-left text-gray-500"></i>
                            </div>
                            <div>
                                <p class="quote-text">"{{ $quote->quote }}"</p>
                            </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
  </div>
    <script src="{{asset('javascript/script.js')}}"></script>
    <script src="{{asset('javascript/app.js')}}"></script>
    {{-- <script src="{{asset('javascript/calendar.js')}}"></script> --}}
</body>
</html>
