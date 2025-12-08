@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
@endsection

@section('content')
<div class="container py-5">
    <div class="notifications-container">
        <div class="notifications-header">
            <h2><i class="bi bi-bell"></i> Thông báo</h2>
            @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                </button>
            </form>
            @endif
        </div>

        <div class="notifications-list">
            @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                <div class="notification-icon">
                    @if($notification->type === 'booking_success')
                    <i class="bi bi-check-circle-fill text-success"></i>
                    @elseif($notification->type === 'booking_cancelled')
                    <i class="bi bi-x-circle-fill text-danger"></i>
                    @elseif($notification->type === 'booking_restored')
                    <i class="bi bi-arrow-clockwise text-success"></i>
                    @else
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    @endif
                </div>

                <div class="notification-content">
                    <p class="notification-message">{{ $notification->message }}</p>
                    <small class="notification-time">
                        <i class="bi bi-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>

                @if(!$notification->is_read)
                <div class="notification-actions">
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-decoration-none" title="Đánh dấu đã đọc">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="empty-notifications">
                <i class="bi bi-bell-slash" style="font-size: 4rem; color: #cbd5e1;"></i>
                <p class="mt-3 text-muted">Bạn chưa có thông báo nào</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
