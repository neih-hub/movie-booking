@extends('layouts.admin')

@section('title', 'Chỉnh sửa rạp chiếu')
@section('page-title', 'Chỉnh sửa rạp chiếu')

@section('content')
<div class="content-card">

    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-edit"></i> Chỉnh sửa rạp chiếu
        </h2>
        <a href="{{ route('admin.cinemas.list') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- FORM UPDATE --}}
    <form action="{{ route('admin.cinemas.update', $cinema->id) }}" method="POST">
        @csrf

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem;">
            <div class="form-group">
                <label>Tên rạp *</label>
                <input type="text" name="name" class="form-control" value="{{ $cinema->name }}" required>
            </div>

            <div class="form-group">
                <label>Thành phố *</label>
                <input type="text" name="city" class="form-control" value="{{ $cinema->city }}" required>
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label>Địa chỉ *</label>
                <input type="text" name="address" class="form-control" value="{{ $cinema->address }}" required>
            </div>
        </div>

        <button class="btn btn-success mt-3" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <i class="fas fa-save"></i> Lưu thay đổi
        </button>
    </form>


    {{-- DANH SÁCH PHÒNG --}}
    <hr class="my-4">

    <h3 style="font-weight:600;">Danh sách phòng</h3>

    <table class="admin-table mt-3">
        <thead>
            <tr>
                <th>Tên phòng</th>
                <th>Tổng ghế</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->seats->count() }}</td>
                <td>
                    <a href="{{ route('admin.rooms.seats.honeycomb', $room->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chair"></i> Xem ghế
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
