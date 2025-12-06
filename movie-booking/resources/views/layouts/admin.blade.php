<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Movie Booking</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="/css/admin.css">

    @stack('styles')
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-film"></i> HubVerse Admin
            </div>

            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.list') }}"
                        class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Quản lý người dùng</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.movies.list') }}"
                        class="nav-link {{ request()->is('admin/movies*') ? 'active' : '' }}">
                        <i class="bi bi-film"></i>
                        <span>Quản lý phim</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.cinemas.list') }}"
                        class="nav-link {{ request()->is('admin/cinemas*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Quản lý rạp chiếu</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.rooms.manage') }}"
                        class="nav-link {{ request()->is('admin/rooms/manage*') ? 'active' : '' }}">
                        <i class="bi bi-door-open"></i>
                        <span>Quản lí phòng</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.showtimes.list') }}"
                        class="nav-link {{ request()->is('admin/showtimes*') ? 'active' : '' }}">
                        <i class="bi bi-clock"></i>
                        <span>Quản lý suất chiếu</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.bookings.list') }}"
                        class="nav-link {{ request()->is('admin/bookings*') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Quản lý đặt vé</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.foods.list') }}"
                        class="nav-link {{ request()->is('admin/foods*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i>
                        <span>Quản lý đồ ăn</span>
                    </a>
                </li>
                <li class="nav-item">
    <a href="{{ route('admin.posts.list') }}" class="nav-link">
        <i class="bi bi-newspaper"></i>
        <span>Quản lý bài viết</span>
    </a>
</li>


                <li class="nav-item"
                    style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                    <a href="/" class="nav-link">
                        <i class="bi bi-globe"></i>
                        <span>Xem trang chủ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link"
                            style="width: 100%; background: none; border: none; text-align: left; cursor: pointer;">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1>@yield('page-title', 'Dashboard')</h1>

                <div class="admin-user-info">
                    @if(Auth::user()->avatar)
                        <img src="/{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f7941e&color=fff"
                            alt="{{ Auth::user()->name }}">
                    @endif
                    <div>
                        <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 0.875rem; color: #64748b;">Administrator</div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    
    {{-- Scripts được yield từ view --}}
    @yield('scripts')
</body>

</html>