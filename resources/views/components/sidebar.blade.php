<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>Admin Panel</h3>
        <button class="close-sidebar" id="close-sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-menu">
        <a href="{{route('app')}}" class="sidebar-menu-item">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{route('users.index')}}" class="sidebar-menu-item">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="{{route('quotes.index')}}" class="sidebar-menu-item">
            <i class="fas fa-quote-left"></i> quotes
        </a>
        <a href="{{ route('mood-streaks.index') }}" class="sidebar-menu-item">
            <i class="fas fa-fire"></i> Mood Streak
        </a>
        <a href="#" class="sidebar-menu-item">
            <i class="fas fa-cog"></i> Settings
        </a>
    </div>
    <div class="sidebar-footer">
        <form action="#" method="POST">
            <button type="submit" class="sidebar-menu-item w-full text-left">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>