@extends('layouts.admin')

@section('title', 'Thêm suất chiếu')
@section('page-title', 'Thêm suất chiếu')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-plus-circle"></i> Thêm suất chiếu mới
            </h2>
            <a href="{{ route('admin.showtimes.list') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.showtimes.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">

                <div class="form-group">
                    <label class="form-label">Phim <span style="color: red;">*</span></label>
                    <select name="movie_id" class="form-select" required>
                        <option value="">Chọn phim</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                {{ $movie->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Rạp chiếu <span style="color: red;">*</span></label>
                    <select name="cinema_id" id="cinema_select" class="form-select" required>
                        <option value="">Chọn rạp</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Phòng chiếu <span style="color: red;">*</span></label>
                    <select name="room_id" id="room_select" class="form-select" required>
                        <option value="">Chọn rạp trước</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ngày chiếu <span style="color: red;">*</span></label>
                    <input type="date" name="date_start" class="form-control" value="{{ old('date_start') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giờ chiếu <span style="color: red;">*</span></label>
                    <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá vé (VNĐ) <span style="color: red;">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', 50000) }}" min="0"
                        required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('admin.showtimes.list') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const cinemaSelect = document.getElementById('cinema_select');
        const roomSelect = document.getElementById('room_select');

        console.log('Admin Showtime Create page loaded');

        cinemaSelect.addEventListener('change', function () {
            const cinemaId = this.value;
            console.log('Selected cinema ID:', cinemaId);

            roomSelect.innerHTML = '<option value="">Đang tải...</option>';

            if (!cinemaId) {
                roomSelect.innerHTML = '<option value="">Chọn rạp trước</option>';
                return;
            }

            fetch(`/api/rooms?cinema_id=${cinemaId}`)
                .then(res => res.json())
                .then(rooms => {
                    console.log('Rooms received:', rooms);

                    roomSelect.innerHTML = '<option value="">Chọn phòng</option>';

                    if (rooms && rooms.length > 0) {
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = room.name;
                            roomSelect.appendChild(option);
                        });
                    } else {
                        roomSelect.innerHTML = '<option value="">Rạp này chưa có phòng</option>';
                    }
                })
                .catch(err => {
                    console.error('Error loading rooms:', err);
                    roomSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                });
        });
    </script>
@endsection