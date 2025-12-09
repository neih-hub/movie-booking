@extends('layouts.admin')

@section('title', 'Thêm món ăn')
@section('page-title', 'Thêm món ăn')

@section('content')

<div class="content-card">

    <div class="card-header">
        <h2 class="card-title"><i class="bi bi-plus-circle"></i> Thêm món ăn</h2>
        <a href="{{ route('admin.foods.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Tên món</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" min="0" required value="{{ old('price') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Số lượng tồn kho</label>
            <input type="number" name="total" class="form-control" min="0" required value="{{ old('total') }}">
        </div>

        <div class="form-group">
            <label class="form-label">Ảnh minh họa</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-primary"><i class="bi bi-save"></i> Lưu</button>
    </form>
</div>

@endsection
