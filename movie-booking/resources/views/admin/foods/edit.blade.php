@extends('layouts.admin')

@section('title', 'Cập nhật món ăn')
@section('page-title', 'Cập nhật món ăn')

@section('content')

<div class="content-card">

    <div class="card-header">
        <h2 class="card-title"><i class="bi bi-pencil-square"></i> Cập nhật món ăn</h2>
        <a href="{{ route('admin.foods.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Tên món</label>
            <input type="text" name="name" class="form-control" required value="{{ $food->name }}">
        </div>

        <div class="form-group">
            <label class="form-label">Giá (VNĐ)</label>
            <input type="number" name="price" class="form-control" min="0" required value="{{ $food->price }}">
        </div>

        <div class="form-group">
            <label class="form-label">Số lượng tồn kho</label>
            <input type="number" name="total" class="form-control" min="0" required value="{{ $food->total }}">
        </div>

        <div class="form-group">
            <label class="form-label">Ảnh hiện tại</label><br>
            @if($food->image)
                <img src="{{ asset('uploads/foods/' . $food->image) }}"
                     style="width:120px; height:120px; object-fit:cover; border-radius:5px;">
            @else
                <p style="color:#777;">Không có ảnh</p>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Chọn ảnh mới (nếu muốn thay)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-primary"><i class="bi bi-save"></i> Cập nhật</button>

    </form>
</div>

@endsection
