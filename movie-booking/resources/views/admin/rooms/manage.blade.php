@extends('layouts.admin')

@section('title', 'Quản lí phòng')
@section('page-title', 'Quản lí phòng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rooms-manage.css') }}">
@endpush

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-door-open"></i> Quản lí phòng chiếu
            </h2>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm phòng mới
            </a>
        </div>

        {{-- Cinema Selector --}}
        <div class="cinema-selector">
            <label for="cinema_select">
                <i class="bi bi-building"></i>
                Chọn rạp chiếu để xem danh sách phòng
            </label>
            <select id="cinema_select" class="form-select">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Rooms Container --}}
        <div id="rooms_container" style="display: none;">
            <div class="rooms-section">
                <div class="rooms-section-title">
                    <i class="bi bi-grid-3x3"></i>
                    <h3>Danh sách phòng chiếu</h3>
                </div>
                <div id="rooms_grid" class="rooms-grid">
                    <!-- Rooms will be loaded via JavaScript -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const cinemas = @json($cinemas);
const cinemaSelect = document.getElementById('cinema_select');
const roomsContainer = document.getElementById('rooms_container');
const roomsGrid = document.getElementById('rooms_grid');

cinemaSelect.addEventListener('change', function () {
    const cinemaId = this.value;

    if (!cinemaId) {
        roomsContainer.style.display = 'none';
        return;
    }

    const cinema = cinemas.find(c => c.id == cinemaId);

    if (cinema && cinema.rooms && cinema.rooms.length > 0) {
        roomsGrid.innerHTML = '';

        cinema.rooms.forEach(room => {
            const roomCard = document.createElement('div');
            roomCard.className = 'room-card';

            roomCard.innerHTML = `
                <div class="room-card-content">
                    <div class="room-name">${room.name}</div>
                    <div class="room-title">Phòng chiếu ${room.name}</div>
                    
                    <div class="room-stats">
                        <div class="room-stat">
                            <span class="room-stat-value">${room.total_seats || 0}</span>
                            <span class="room-stat-label">Tổng ghế</span>
                        </div>
                        <div class="room-stat">
                            <span class="room-stat-value">
                                <i class="bi bi-grid-3x3"></i>
                            </span>
                            <span class="room-stat-label">Sơ đồ ghế</span>
                        </div>
                    </div>
                    
                    <div class="room-actions">
                        <a href="/admin/rooms/edit/${room.id}" class="btn btn-warning edit-btn" onclick="event.stopPropagation()">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <button class="btn btn-danger delete-btn" data-room-id="${room.id}" onclick="event.stopPropagation()">
                            <i class="bi bi-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            `;

            // Delete button handler
            const deleteBtn = roomCard.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (confirm(`Bạn có chắc muốn xóa phòng ${room.name}?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/rooms/delete/${room.id}`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Click to view seats
            roomCard.addEventListener('click', function () {
                window.location.href = `/admin/rooms/${room.id}/seats-honeycomb`;
            });

            roomsGrid.appendChild(roomCard);
        });

        roomsContainer.style.display = 'block';
    } else {
        roomsGrid.innerHTML = `
            <div class="empty-rooms" style="grid-column: 1 / -1;">
                <i class="bi bi-door-closed"></i>
                <p>Rạp này chưa có phòng chiếu nào</p>
            </div>
        `;
        roomsContainer.style.display = 'block';
    }
});
</script>
@endpush