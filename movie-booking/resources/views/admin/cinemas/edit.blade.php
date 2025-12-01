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

        <form action="{{ route('admin.cinemas.update', $cinema->id) }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Tên rạp <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $cinema->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $cinema->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cinema->email) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ <span style="color: red;">*</span></label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $cinema->address) }}"
                    required>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="{{ route('admin.cinemas.list') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
@endsection