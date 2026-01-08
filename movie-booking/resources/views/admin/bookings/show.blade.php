@extends('layouts.admin')

@section('title', 'Chi tiết đặt vé')
@section('page-title', 'Chi tiết đặt vé')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-info-circle"></i> Chi tiết đặt vé #{{ $booking->id }}
            </h2>
            <a href="{{ route('admin.bookings.list') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            
            <div>
                <h3 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--admin-primary);">
                    <i class="fas fa-user"></i> Thông tin người dùng
                </h3>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <p><strong>Tên:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $booking->user->phone ?? 'N/A' }}</p>
                </div>
            </div>

            
            <div>
                <h3 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--admin-primary);">
                    <i class="fas fa-film"></i> Thông tin phim & suất chiếu
                </h3>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <p><strong>Phim:</strong> {{ $booking->showtime->movie->title }}</p>
                    <p><strong>Rạp:</strong> {{ $booking->showtime->room->cinema->name }}</p>
                    <p><strong>Phòng:</strong> {{ $booking->showtime->room->name }}</p>
                    <p><strong>Thời gian:</strong> {{ \Carbon\Carbon::parse($booking->showtime->date_start . ' ' . $booking->showtime->start_time)->format('d/m/Y H:i') }}</p>
                    <p><strong>Giá vé:</strong> {{ number_format($booking->showtime->price) }} VNĐ</p>
                </div>
            </div>

            <div>
                <h3 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--admin-primary);">
                    <i class="fas fa-ticket-alt"></i> Thông tin đặt vé
                </h3>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <p><strong>Mã đặt vé:</strong> #{{ $booking->id }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Tổng tiền:</strong> <span
                            style="color: var(--admin-success); font-size: 1.25rem; font-weight: 700;">{{ number_format($booking->total_price) }}
                            VNĐ</span></p>
                    <p>
                        <strong>Trạng thái:</strong>
                        @if($booking->status == 1)
                            <span class="badge badge-success">Đã thanh toán</span>
                        @else
                            <span class="badge badge-danger">Đã hủy</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if($booking->bookingSeats && $booking->bookingSeats->count() > 0)
            <div style="margin-top: 2rem;">
                <h3 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--admin-primary);">
                    <i class="fas fa-couch"></i> Danh sách ghế đã đặt
                </h3>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($booking->bookingSeats as $bookingSeat)
                            <span class="badge badge-info" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                {{ $bookingSeat->seat->seat_number }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($booking->bookingFoods && $booking->bookingFoods->count() > 0)
            <div style="margin-top: 2rem;">
                <h3 style="font-size: 1.125rem; margin-bottom: 1rem; color: var(--admin-primary);">
                    <i class="fas fa-utensils"></i> Thức ăn & đồ uống đã đặt
                </h3>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px;">
                    <table style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e5e7eb;">
                                <th style="padding: 0.5rem; text-align: left;">Tên món</th>
                                <th style="padding: 0.5rem; text-align: center;">Số lượng</th>
                                <th style="padding: 0.5rem; text-align: right;">Đơn giá</th>
                                <th style="padding: 0.5rem; text-align: right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->bookingFoods as $bookingFood)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 0.5rem;">{{ $bookingFood->food->name }}</td>
                                    <td style="padding: 0.5rem; text-align: center;">{{ $bookingFood->quantity }}</td>
                                    <td style="padding: 0.5rem; text-align: right;">{{ number_format($bookingFood->price) }} VNĐ</td>
                                    <td style="padding: 0.5rem; text-align: right; font-weight: 600;">
                                        {{ number_format($bookingFood->price * $bookingFood->quantity) }} VNĐ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #f1f5f9; display: flex; gap: 1rem;">
            @if($booking->status == 1)
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn hủy vé này?')">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-ban"></i> Hủy vé
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST"
                onsubmit="return confirm('Bạn có chắc muốn xóa đặt vé này?')">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        </div>
    </div>
@endsection