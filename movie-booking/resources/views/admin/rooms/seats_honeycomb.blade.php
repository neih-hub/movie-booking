@extends('layouts.admin')

@section('title', 'Sơ đồ ghế - Phòng ' . $room->name)
@section('page-title', 'Sơ đồ ghế - Phòng ' . $room->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seats-honeycomb.css') }}">
@endpush

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

        <div class="seats-layout">
            {{-- Seat Map --}}
            <div class="seat-container">
                <div class="screen"></div>

                <div class="honeycomb-grid">
                    @foreach($seatRows as $rowIndex => $seats)
                        <div class="seat-row">
                            @foreach($seats as $seat)
                                <div class="seat" data-seat-id="{{ $seat->id }}" data-seat-number="{{ $seat->seat_number }}"
                                    data-room-name="{{ $room->name }}" data-cinema-name="{{ $room->cinema->name }}"
                                    title="{{ $seat->seat_number }}">
                                    {{ $seat->seat_number }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="info-panel">
                <div class="info-panel-header">
                    <h3><i class="bi bi-info-circle"></i> Thông tin ghế</h3>
                    <p>Click vào ghế để xem chi tiết</p>
                </div>

                <div id="seat-info-content">
                    <div class="empty-seat-message">
                        <i class="bi bi-hand-index"></i>
                        <h5>Chưa chọn ghế</h5>
                        <p>Vui lòng click vào một ghế để xem thông tin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const seatsData = @json($seatsWithBookings);

        document.querySelectorAll('.seat').forEach(seat => {
            const seatId = seat.dataset.seatId;
            const seatData = seatsData.find(s => s.id == seatId);

            //đánh dấu seat đã đc đặt
            if (seatData && seatData.booking) {
                seat.classList.add('occupied');
            }

            seat.addEventListener('click', function () {
                document.querySelectorAll('.seat').forEach(s => s.classList.remove('selected'));

                this.classList.add('selected');

                showSeatInfo(seatData);
            });
        });

        function showSeatInfo(seatData) {
            const infoContent = document.getElementById('seat-info-content');

            if (!seatData) return;

            let html = `
            <div class="seat-info-section">
                <h4><i class="bi bi-geo-alt"></i> Thông tin ghế</h4>
                <div class="info-item">
                    <span class="info-item-label">Số ghế</span>
                    <span class="info-item-value">${seatData.seat_number}</span>
                </div>
                <div class="info-item">
                    <span class="info-item-label">Phòng chiếu</span>
                    <span class="info-item-value">${seatData.room_name}</span>
                </div>
                <div class="info-item">
                    <span class="info-item-label">Rạp</span>
                    <span class="info-item-value">${seatData.cinema_name}</span>
                </div>
            </div>
        `;

            if (seatData.booking) {
                const user = seatData.booking.user;
                html += `
                <div class="seat-info-section">
                    <h4><i class="bi bi-person-fill"></i> Thông tin người đặt</h4>
                    <div class="info-item">
                        <span class="info-item-label">Họ tên</span>
                        <span class="info-item-value">${user.name || 'N/A'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Email</span>
                        <span class="info-item-value">${user.email || 'N/A'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Số điện thoại</span>
                        <span class="info-item-value">${user.phone || 'N/A'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Giới tính</span>
                        <span class="info-item-value">${user.gender === 'male' ? 'Nam' : user.gender === 'female' ? 'Nữ' : 'Khác'}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-item-label">Mã đặt vé</span>
                        <span class="info-item-value">#${seatData.booking.id}</span>
                    </div>
                </div>
            `;
            } else {
                html += `
                <div class="empty-seat-message">
                    <i class="bi bi-check-circle"></i>
                    <h5>Ghế trống</h5>
                    <p>Hiện chưa có ai ngồi ở đây</p>
                </div>
            `;
            }

            infoContent.innerHTML = html;
        }
    </script>
@endpush