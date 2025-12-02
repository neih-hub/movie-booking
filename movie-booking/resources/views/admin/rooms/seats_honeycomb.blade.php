@extends('layouts.admin')

@section('title', 'Sơ đồ ghế - Phòng ' . $room->name)
@section('page-title', 'Sơ đồ ghế - Phòng ' . $room->name)

@section('styles')
    <style>
        .seat-container {
            background: #f8fafc;
            padding: 3rem;
            border-radius: 16px;
            margin-top: 2rem;
        }

        .cinema-info {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .cinema-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .cinema-info p {
            color: #64748b;
            margin: 0;
        }

        .screen {
            background: linear-gradient(to bottom, #e2e8f0 0%, #cbd5e1 100%);
            height: 8px;
            border-radius: 50%;
            margin: 2rem auto 3rem;
            max-width: 80%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .screen::before {
            content: 'MÀN HÌNH';
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 2px;
        }

        .honeycomb-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .seat-row {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }


        .seat {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            color: #374151;
        }

        .seat:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-card .label {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-grid-3x3"></i> Sơ đồ ghế - Phòng {{ $room->name }}
            </h2>
            <a href="{{ route('admin.rooms.manage') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="cinema-info">
            <h3>{{ $room->cinema->name }}</h3>
            <p>Phòng chiếu {{ $room->name }} - {{ $room->total_seats }} ghế</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="number">{{ count($seatRows) }}</div>
                <div class="label">Số hàng ghế</div>
            </div>
            <div class="stat-card">
                <div class="number">{{ $room->total_seats }}</div>
                <div class="label">Tổng số ghế</div>
            </div>
            <div class="stat-card">
                <div class="number">{{ isset($seatRows[0]) ? count($seatRows[0]) : 0 }}</div>
                <div class="label">Ghế mỗi hàng</div>
            </div>
        </div>

        <div class="seat-container">
            <div class="screen"></div>

            <div class="honeycomb-grid">
                @foreach($seatRows as $rowIndex => $seats)
                    <div class="seat-row">
                        @foreach($seats as $seat)
                            <div class="seat" title="{{ $seat->seat_number }}">
                                {{ $seat->seat_number }}
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection