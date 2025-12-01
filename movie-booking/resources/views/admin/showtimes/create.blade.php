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
                    <label class="form-label">Thời gian chiếu <span style="color: red;">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Giá vé (VNĐ) <span style="color: red;">*</span></label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', 50000) }}" min="0"
                        required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('admin.showtimes.list') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            // Dynamic room loading based on cinema selection
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