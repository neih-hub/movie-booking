@extends('layouts.admin')

@section('title', 'Chỉnh sửa suất chiếu')
@section('page-title', 'Chỉnh sửa suất chiếu')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-edit"></i> Chỉnh sửa suất chiếu
            </h2>
            <a href="{{ route('admin.showtimes.list') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.showtimes.update', $showtime->id) }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Phim <span style="color: red;">*</span></label>
                    <select name="movie_id" class="form-select" required>
                        <option value="">Chọn phim</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id', $showtime->movie_id) == $movie->id ? 'selected' : '' }}>
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
                            <option value="{{ $cinema->id }}" {{ $showtime->room->cinema_id == $cinema->id ? 'selected' : '' }}>
                                {{ $cinema->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Phòng chiếu <span style="color: red;">*</span></label>
                    <select name="room_id" id="room_select" class="form-select" required>
                        <option value="">Chọn phòng</option>
                        @foreach($showtime->room->cinema->rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $showtime->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Thời gian chiếu <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-control"
                        value="{{ old('start_time', $showtime->start_time->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá vé (VNĐ) <span style="color: red;">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $showtime->price) }}"
                        min="0" required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="{{ route('admin.showtimes.list') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            const cinemaSelect = document.getElementById('cinema_select');
            const roomSelect = document.getElementById('room_select');
            const cinemas = @json($cinemas);

            cinemaSelect.addEventListener('change', function () {
                const cinemaId = this.value;
                roomSelect.innerHTML = '<option value="">Chọn phòng</option>';

                if (cinemaId) {
                    const cinema = cinemas.find(c => c.id == cinemaId);
                    if (cinema && cinema.rooms) {
                        cinema.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = room.name;
                            roomSelect.appendChild(option);
                        });
                    }
                }
            });
        </script>
    @endsection
@endsection