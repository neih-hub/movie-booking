@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

  <!-- Statistics Cards -->
  <div class="stats-grid">

    <div class="stat-card">
      <div class="stat-content">
        <div class="stat-info">
          <div class="stat-label">Tổng người dùng</div>
          <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-people"></i>
        </div>
      </div>
    </div>

    <div class="stat-card success">
      <div class="stat-content">
        <div class="stat-info">
          <div class="stat-label">Tổng phim</div>
          <div class="stat-value">{{ number_format($totalMovies ?? 0) }}</div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-film"></i>
        </div>
      </div>
    </div>

    <div class="stat-card warning">
      <div class="stat-content">
        <div class="stat-info">
          <div class="stat-label">Tổng rạp chiếu</div>
          <div class="stat-value">{{ number_format($totalCinemas ?? 0) }}</div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-building"></i>
        </div>
      </div>
    </div>

    <div class="stat-card danger">
      <div class="stat-content">
        <div class="stat-info">
          <div class="stat-label">Tổng đặt vé</div>
          <div class="stat-value">{{ number_format($totalBookings ?? 0) }}</div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-ticket-perforated"></i>
        </div>
      </div>
    </div>

    <div class="stat-card info">
      <div class="stat-content">
        <div class="stat-info">
          <div class="stat-label">Tổng đồ ăn</div>
          <div class="stat-value">{{ number_format($totalFoods ?? 0) }}</div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-shop"></i>
        </div>
      </div>
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

    @if(isset($recentBookings) && $recentBookings->count() > 0)
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
                <td><strong>#{{ $booking->id }}</strong></td>
                <td>
                  <i class="bi bi-person-circle"></i>
                  {{ $booking->user->name ?? 'N/A' }}
                </td>
                <td>
                  <i class="bi bi-film"></i>
                  {{ $booking->showtime->movie->title ?? 'N/A' }}
                </td>
                <td><strong>{{ number_format($booking->total_price ?? 0) }} VNĐ</strong></td>
                <td>
                  @if($booking->status == 1)
                    <span class="badge badge-success">
                      <i class="bi bi-check-circle"></i> Đã thanh toán
                    </span>
                  @else
                    <span class="badge badge-danger">
                      <i class="bi bi-x-circle"></i> Đã hủy
                    </span>
                  @endif
                </td>
                <td>
                  <i class="bi bi-calendar3"></i>
                  {{ $booking->created_at->format('d/m/Y H:i') }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Chưa có đặt vé nào</p>
      </div>
    @endif
  </div>


  <!-- Quick Actions -->
  <div class="content-card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="bi bi-lightning-charge"></i> Thao tác nhanh
      </h2>
    </div>

    <div class="quick-actions-grid">

      <a href="{{ route('admin.movies.create') }}" class="quick-action-btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        <span>Thêm phim mới</span>
      </a>

      <a href="{{ route('admin.cinemas.create') }}" class="quick-action-btn btn-success">
        <i class="bi bi-plus-circle"></i>
        <span>Thêm rạp chiếu</span>
      </a>

      <a href="{{ route('admin.showtimes.create') }}" class="quick-action-btn btn-warning">
        <i class="bi bi-plus-circle"></i>
        <span>Thêm suất chiếu</span>
      </a>

      <a href="{{ route('admin.users.list') }}" class="quick-action-btn btn-secondary">
        <i class="bi bi-gear"></i>
        <span>Quản lý users</span>
      </a>

      <a href="{{ route('admin.foods.list') }}" class="quick-action-btn btn-info">
        <i class="bi bi-shop"></i>
        <span>Quản lý đồ ăn</span>
      </a>

    </div>
  </div>

@endsection
