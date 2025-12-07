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
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .success-container {
            max-width: 800px;
            width: 100%;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 3rem;
            color: #10b981;
        }

        .success-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .success-header p {
            font-size: 1.125rem;
            opacity: 0.9;
            margin: 0;
        }

        .success-body {
            padding: 2.5rem;
        }

        .booking-details {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 600;
            text-align: right;
        }

        .total-section {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .total-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .total-value {
            font-size: 2rem;
            font-weight: 700;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-custom {
            padding: 1rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: white;
            color: #64748b;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary-custom:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #64748b;
        }

        .seat-list, .food-list {
            margin-top: 0.5rem;
            padding-left: 1rem;
        }

        .seat-list li, .food-list li {
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        @media (max-width: 768px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }

            .success-header h1 {
                font-size: 1.5rem;
            }

            .total-value {
                font-size: 1.5rem;
            }
        }
    </style>
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
