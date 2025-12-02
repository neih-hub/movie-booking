@extends('layouts.admin')

@section('title', 'Thêm phòng chiếu')
@section('page-title', 'Thêm phòng chiếu mới')
@section('page-subtitle', 'Tạo phòng chiếu mới cho rạp')

@section('content')
    <div class="content-card">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cinema_id" class="form-label">Rạp chiếu <span class="text-danger">*</span></label>
                    <select name="cinema_id" id="cinema_id" class="form-select" required>
                        <option value="">-- Chọn rạp --</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" {{ old('cinema_id') == $cinema->id ? 'selected' : '' }}>
                                {{ $cinema->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Tên phòng <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        placeholder="VD: Phòng 1, Room A" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="total_seats" class="form-label">Tổng số ghế <span class="text-danger">*</span></label>
                    <input type="number" name="total_seats" id="total_seats" class="form-control"
                        value="{{ old('total_seats') }}" placeholder="VD: 100" min="1" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="seats_per_row" class="form-label">Số ghế mỗi hàng <span class="text-danger">*</span></label>
                    <input type="number" name="seats_per_row" id="seats_per_row" class="form-control"
                        value="{{ old('seats_per_row', 10) }}" placeholder="VD: 10" min="1" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="rows" class="form-label">Số hàng ghế <span class="text-danger">*</span></label>
                    <input type="number" name="rows" id="rows" class="form-control" value="{{ old('rows', 10) }}"
                        placeholder="VD: 10" min="1" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu phòng chiếu
                </button>
                <a href="{{ route('admin.rooms.manage') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
@endsection