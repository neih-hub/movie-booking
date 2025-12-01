@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
  <!-- Statistics Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="bi bi-people"></i>
      </div>
      <div class="stat-label">Tổng người dùng</div>
      <div class="stat-value">{{ $totalUsers }}</div>
    </div>

    <div class="stat-card success">
      <div class="stat-icon">
        <i class="bi bi-film"></i>
      </div>
      <div class="stat-label">Tổng phim</div>
      <div class="stat-value">{{ $totalMovies }}</div>
    </div>

    <div class="stat-card warning">
      <div class="stat-icon">
        <i class="bi bi-building"></i>
      </div>
      <div class="stat-label">Tổng rạp chiếu</div>
      <div class="stat-value">{{ $totalCinemas }}</div>
    </div>

    <div class="stat-card danger">
      <div class="stat-icon">
        <i class="bi bi-ticket-perforated"></i>
      </div>
      <div class="stat-label">Tổng đặt vé</div>
      <div class="stat-value">{{ $totalBookings }}</div>
    </div>
  </div>

  <!-- Recent Bookings -->
  <div class="content-card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="bi bi-clock"></i> Đặt vé gần đây
      </h2>
      <a href="{{ route('admin.bookings.list') }}" class="btn btn-primary">
        Xem tất cả <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    @if($recentBookings->count() > 0)
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Người dùng</th>
              <th>Phim</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th>Ngày đặt</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentBookings as $booking)
              <tr>
                <td>#{{ $booking->id }}</td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                <td>{{ number_format($booking->total_price ?? 0) }} VNĐ</td>
                <td>
                  @if($booking->status == 1)
                    <span class="badge badge-success">Đã thanh toán</span>
                  @else
                    <span class="badge badge-danger">Đã hủy</span>
                  @endif
                </td>
                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p style="text-align: center; color: #64748b; padding: 2rem;">Chưa có đặt vé nào</p>
    @endif
  </div>

  <!-- Quick Actions -->
  <div class="content-card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="bi bi-lightning-charge"></i> Thao tác nhanh
      </h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
      <a href="{{ route('admin.movies.create') }}" class="btn btn-primary" style="text-align: center; padding: 1.5rem;">
        <i class="bi bi-plus-circle"></i><br>
        Thêm phim mới
      </a>

      <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success" style="text-align: center; padding: 1.5rem;">
        <i class="bi bi-plus-circle"></i><br>
        Thêm rạp chiếu
      </a>

      <a href="{{ route('admin.showtimes.create') }}" class="btn btn-warning"
        style="text-align: center; padding: 1.5rem;">
        <i class="bi bi-plus-circle"></i><br>
        Thêm suất chiếu
      </a>

      <a href="{{ route('admin.users.list') }}" class="btn btn-primary" style="text-align: center; padding: 1.5rem;">
        <i class="bi bi-gear"></i><br>
        Quản lý users
      </a>
    </div>
  </div>
@endsection