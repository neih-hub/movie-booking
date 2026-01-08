@extends('layouts.admin')

@section('title', 'Thêm rạp chiếu')
@section('page-title', 'Thêm rạp chiếu')

@section('content')
<div class="content-card">

    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-plus-circle"></i> Thêm rạp chiếu mới
        </h2>
        <a href="{{ route('admin.cinemas.list') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.cinemas.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">

            <div class="form-group">
                <label class="form-label">Tên rạp <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Thành phố <span style="color: red;">*</span></label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
            </div>

        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
            <label class="form-label">Địa chỉ <span style="color: red;">*</span></label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>

            <a href="{{ route('admin.cinemas.list') }}" class="btn btn-danger">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>

    </form>
</div>
@endsection
