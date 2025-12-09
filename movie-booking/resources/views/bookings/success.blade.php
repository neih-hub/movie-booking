<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt vé thành công</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/booking-success.css') }}">
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h1>Đặt vé thành công!</h1>
                <p>Mã đặt vé: #{{ $booking->id }}</p>
            </div>

            <div class="success-body">
                <div class="booking-details">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
                        Thông tin đặt vé
                    </h3>

                    <div class="detail-row">
                        <span class="detail-label">Phim</span>
                        <span class="detail-value">{{ $booking->showtime->movie->title }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Rạp</span>
                        <span class="detail-value">{{ $booking->showtime->room->cinema->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Phòng</span>
                        <span class="detail-value">{{ $booking->showtime->room->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Ngày chiếu</span>
                        <span class="detail-value">
                            {{ \Carbon\Carbon::parse($booking->showtime->date_start)->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Giờ chiếu</span>
                        <span class="detail-value">
                            {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}
                        </span>
                    </div>

                    @if($booking->bookingSeats->count() > 0)
                    <div class="detail-row">
                        <span class="detail-label">Ghế đã đặt</span>
                        <span class="detail-value">
                            {{ $booking->bookingSeats->count() }} ghế
                        </span>
                    </div>
                    <ul class="seat-list">
                        @foreach($booking->bookingSeats as $bookingSeat)
                        <li>{{ $bookingSeat->seat->seat_number }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($booking->bookingFoods->count() > 0)
                    <div class="detail-row" style="margin-top: 1rem;">
                        <span class="detail-label">Thức ăn & đồ uống</span>
                        <span class="detail-value"></span>
                    </div>
                    <ul class="food-list">
                        @foreach($booking->bookingFoods as $bookingFood)
                        <li>
                            {{ $bookingFood->food->name }} x{{ $bookingFood->quantity }}
                            - {{ number_format($bookingFood->price * $bookingFood->quantity) }} đ
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="total-section">
                    <div class="total-label">Tổng thanh toán</div>
                    <div class="total-value">{{ number_format($booking->total_price) }} đ</div>
                </div>

                <div class="action-buttons">
                    <a href="/" class="btn-custom btn-secondary-custom">
                        <i class="bi bi-house"></i>
                        Về trang chủ
                    </a>
                    <a href="/profile#history" class="btn-custom btn-primary-custom">
                        <i class="bi bi-clock-history"></i>
                        Xem lịch sử đặt vé
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
