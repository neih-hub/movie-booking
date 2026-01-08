@extends('layouts.admin')

@section('title', 'Thêm ghế ngồi')
@section('page-title', 'Thêm ghế ngồi')
@section('page-subtitle', 'Tạo ghế mới hoặc tạo hàng loạt')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="content-card">
                <h5 class="mb-3"><i class="fas fa-chair"></i> Thêm ghế đơn lẻ</h5>
                <form action="{{ route('admin.seats.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="room_id" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                        <select name="room_id" id="room_id" class="form-select" required>
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->cinema->name }} - {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="row" class="form-label">Hàng <span class="text-danger">*</span></label>
                        <input type="text" name="row" id="row" class="form-control" placeholder="VD: A, B, C" maxlength="5"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="seat_number" class="form-label">Số ghế <span class="text-danger">*</span></label>
                        <input type="text" name="seat_number" id="seat_number" class="form-control" placeholder="VD: A1, B5"
                            maxlength="10" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Loại ghế <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="normal">Ghế thường</option>
                            <option value="vip">Ghế VIP</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu ghế
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="content-card">
                <h5 class="mb-3"><i class="fas fa-layer-group"></i> Tạo ghế hàng loạt</h5>
                <form action="{{ route('admin.seats.bulk') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="bulk_room_id" class="form-label">Phòng chiếu <span class="text-danger">*</span></label>
                        <select name="room_id" id="bulk_room_id" class="form-select" required>
                            <option value="">-- Chọn phòng --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->cinema->name }} - {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rows" class="form-label">Số hàng <span class="text-danger">*</span></label>
                        <input type="number" name="rows" id="rows" class="form-control" placeholder="VD: 10" min="1"
                            max="26" required>
                        <small class="text-muted">Tối đa 26 hàng (A-Z)</small>
                    </div>

                    <div class="mb-3">
                        <label for="seats_per_row" class="form-label">Số ghế mỗi hàng <span
                                class="text-danger">*</span></label>
                        <input type="number" name="seats_per_row" id="seats_per_row" class="form-control"
                            placeholder="VD: 12" min="1" max="50" required>
                    </div>

                    <div class="mb-3">
                        <label for="bulk_type" class="form-label">Loại ghế <span class="text-danger">*</span></label>
                        <select name="type" id="bulk_type" class="form-select" required>
                            <option value="normal">Ghế thường</option>
                            <option value="vip">Ghế VIP</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Ghế sẽ được tạo tự động theo định dạng: A1, A2, B1, B2, ...
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-magic"></i> Tạo hàng loạt
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.seats.list') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
@endsection