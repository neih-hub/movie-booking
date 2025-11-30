<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 header-wrapper">
  <div class="container position-relative">

    {{-- Logo --}}
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="/image/logo.png" height="42" alt="Galaxy Logo">
      <span class="ms-2 fw-bold fs-4 text-primary">HubVese</span>
    </a>

    {{-- Mobile Toggle --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Menu --}}
    <div class="collapse navbar-collapse" id="navbarContent">

      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-center">

        {{-- Mua vé --}}
        <li class="nav-item mx-3">
          <a href="/booking" class="btn btn-warning fw-bold px-4 py-2 rounded-3 buy-btn">
            ⭐ Mua Vé
          </a>
        </li>

        {{-- Phim --}}
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Phim</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/movies">Đang Chiếu</a></li>
            <li><a class="dropdown-item" href="/movies/coming">Sắp Chiếu</a></li>
          </ul>
        </li>

        {{-- Star Shop --}}
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Star Shop</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Ưu đãi</a></li>
            <li><a class="dropdown-item" href="#">Thẻ thành viên</a></li>
          </ul>
        </li>

        {{-- Góc Điện Ảnh --}}
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Góc Điện Ảnh</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Blog</a></li>
            <li><a class="dropdown-item" href="#">Review</a></li>
          </ul>
        </li>

        {{-- Rạp/Giá Vé --}}
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Rạp / Giá Vé</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/theaters">Danh sách rạp</a></li>
            <li><a class="dropdown-item" href="/prices">Giá vé</a></li>
          </ul>
        </li>

      </ul>

      {{-- Search container --}}
      <div class="search-container d-flex align-items-center me-3">

        {{-- Icon search --}}
        <i class="bi bi-search fs-4 search-icon" id="openSearch"></i>

        {{-- Input search --}}
        <input type="text" id="searchInput" class="form-control search-input d-none"
          placeholder="Tìm phim, thể loại, diễn viên...">

        {{-- Kết quả gợi ý --}}
        <div id="searchResults" class="search-results list-group d-none"></div>

      </div>

      {{-- Auth --}}
      @if(Auth::check())
      <div class="dropdown">
        <a class="nav-link dropdown-toggle fw-bold text-primary" href="#" data-bs-toggle="dropdown">
          👋 Xin chào, {{ Auth::user()->name }}
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          {{-- Nếu là admin → hiện Admin Panel --}}
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

          {{-- Logout form --}}
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
      <a href="/login" class="nav-link fw-bold mx-2">Đăng Nhập</a>
      <a href="/register" class="nav-link fw-bold text-primary">Tham Gia</a>
      @endif

    </div>

  </div>
</nav>