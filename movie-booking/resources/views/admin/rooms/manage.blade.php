@extends('layouts.admin')

@section('title', 'Quản lí phòng')
@section('page-title', 'Quản lí phòng')

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

        <div class="form-group" style="max-width: 500px; margin: 2rem 0;">
            <label class="form-label">Chọn rạp chiếu</label>
            <select id="cinema_select" class="form-select">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="rooms_container" style="display: none;">
            <h3 style="margin: 2rem 0 1rem; font-size: 1.25rem; font-weight: 600;">Danh sách phòng</h3>
            <div id="rooms_grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                <!-- Rooms via JavaScript -->
            </div>
        </div>
    </div>

    @section('scripts')
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
                        roomCard.style.cssText = `
                                            background: gray;
                                            padding: 2rem;
                                            border-radius: 12px;
                                            color: white;
                                            text-align: center;
                                            cursor: pointer;
                                            transition: transform 0.2s, box-shadow 0.2s;
                                            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                                        `;

                        roomCard.innerHTML = `
                                            <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">
                                                ${room.name}
                                            </div>
                                            <div style="font-size: 1rem; opacity: 0.9;">
                                                Phòng chiếu ${room.name}
                                            </div>
                                            <div style="font-size: 0.875rem; opacity: 0.8; margin-top: 0.5rem;">
                                                ${room.total_seats} ghế
                                            </div>
                                            <div style="display: flex; gap: 0.5rem; margin-top: 1rem; justify-content: center;">
                                                <a href="/admin/rooms/edit/${room.id}" class="btn btn-sm btn-warning edit-btn" style="color: white;">
                                                    <i class="bi bi-pencil"></i> Sửa
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn" data-room-id="${room.id}">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </button>
                                            </div>
                                        `;

                        // Add event listeners
                        const editBtn = roomCard.querySelector('.edit-btn');
                        const deleteBtn = roomCard.querySelector('.delete-btn');

                        editBtn.addEventListener('click', function (e) {
                            e.stopPropagation();
                        });

                        deleteBtn.addEventListener('click', function (e) {
                            e.stopPropagation();
                            if (confirm('Bạn có chắc muốn xóa phòng này?')) {
                                // Create and submit form
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

                        roomCard.addEventListener('mouseenter', function () {
                            this.style.transform = 'translateY(-5px)';
                            this.style.boxShadow = '0 8px 16px rgba(0,0,0,0.2)';
                        });

                        roomCard.addEventListener('mouseleave', function () {
                            this.style.transform = 'translateY(0)';
                            this.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                        });

                        roomCard.addEventListener('click', function () {
                            window.location.href = `/admin/rooms/${room.id}/seats-honeycomb`;
                        });

                        roomsGrid.appendChild(roomCard);
                    });

                    roomsContainer.style.display = 'block';
                } else {
                    roomsGrid.innerHTML = '<p style="color: #64748b; text-align: center; padding: 2rem;">Rạp này chưa có phòng chiếu nào.</p>';
                    roomsContainer.style.display = 'block';
                }
            });
        </script>
    @endsection
@endsection