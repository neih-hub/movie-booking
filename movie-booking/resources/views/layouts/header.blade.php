<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 header-wrapper">
  <div class="container position-relative">

    {{-- Logo --}}
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="/image/logo.png" height="42" alt="Galaxy Logo">
      <span class="ms-2 brand-text">HubVerse</span>
    </a>

    {{-- Mobile Toggle --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Menu --}}
    <div class="collapse navbar-collapse" id="navbarContent">

      <!-- Navigation Menu -->
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center">
        <!-- Mua vé button -->
        <li class="nav-item mx-3">
          <a href="/booking" class="btn btn-warning fw-bold px-4 py-2 rounded-3 buy-btn">
            ⭐ Mua Vé
          </a>
        </li>
        <li class="nav-item dropdown mx-2">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Phim
          </a>
          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item" href="/movies?filter=now_showing">
                Đang chiếu
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="/movies?filter=coming_soon">
                Sắp chiếu
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item dropdown mx-2">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            Rạp / Giá vé
          </a>
          <ul class="dropdown-menu">
            @if(isset($cinemas) && $cinemas->count() > 0)
              @foreach($cinemas as $cinema)
              <li>
                <a class="dropdown-item" href="{{ route('theater.show', $cinema->id) }}">
                  {{ $cinema->name }}
                </a>
              </li>
              @endforeach
            @else
              <li><a class="dropdown-item" href="#">Không có rạp</a></li>
            @endif
          </ul>
        </li>

        <li class="nav-item mx-2">
          <a href="/posts" class="nav-link {{ request()->is('posts*') ? 'active' : '' }}">
            Góc Điện Ảnh
          </a>
        </li>
      </ul>

      {{-- Notifications Bell (for authenticated users) --}}
      @if(Auth::check())
      <div class="notification-bell me-3">
        <a href="{{ route('notifications.index') }}" class="nav-link position-relative">
          <i class="bi bi-bell fs-4"></i>
          @if(Auth::user()->notifications()->unread()->count() > 0)
          <span class="notification-badge">
            {{ Auth::user()->notifications()->unread()->count() }}
          </span>
          @endif
        </a>
      </div>
      @endif

      {{-- tìm kiếm --}}
      <div class="search-container d-flex align-items-center me-3">
        <i class="bi bi-search fs-4 search-icon" id="openSearch"></i>
        <input type="text" id="searchInput" class="form-control search-input d-none"
          placeholder="Nhập tên phim...">
        <div id="searchResults" class="search-results list-group d-none"></div>
      </div>

      {{-- Auth --}}
      @if(Auth::check())
      <div class="dropdown">
        <a class="nav-link dropdown-toggle user-greeting" href="#" data-bs-toggle="dropdown">
          👋 Xin chào, {{ Auth::user()->name }}
        </a>

        <ul class="dropdown-menu dropdown-menu-end">

          {{-- Nếu là admin --}}
          @if(Auth::user()->role === 0)
          <li>
            <a class="dropdown-item text-danger fw-bold" href="/admin">
              🛠 Quản lý rạp
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          @endif

          <li><a class="dropdown-item" href="/profile">Thông tin cá nhân</a></li>
          <li><a class="dropdown-item" href="/bookings">Lịch sử đặt vé</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>

          {{-- Logout --}}
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
              </button>
            </form>
          </li>

        </ul>
      </div>

      @else
      <a href="/login" class="nav-link fw-bold mx-2 auth-link">Đăng Nhập</a>
      <a href="/register" class="nav-link fw-bold auth-link" style="color: #667eea !important;">Tham Gia</a>
      @endif

    </div>

  </div>
</nav>