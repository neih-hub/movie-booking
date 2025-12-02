@extends('layouts.admin')

@section('title', 'Quản lý đồ ăn')
@section('page-title', 'Quản lý đồ ăn')

@section('content')

<div class="content-card">

    <div class="card-header">
        <h2 class="card-title"><i class="bi bi-shop"></i> Danh sách đồ ăn</h2>

        <a href="{{ route('admin.foods.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm món ăn
        </a>
    </div>

    <!-- Search -->
    <form method="GET" action="{{ route('admin.foods.list') }}" style="margin-bottom: 1rem;">
        <div style="display: flex; gap: .5rem; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên..."
                value="{{ request('search') }}">
            <button class="btn btn-info"><i class="bi bi-search"></i> Tìm</button>
        </div>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên món</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            @forelse($foods as $food)
                <tr>
                    <td>
                        @if($food->image)
                            <img src="{{ asset('uploads/foods/' . $food->image) }}"
                                 style="width:60px; height:60px; object-fit:cover; border-radius:5px;">
                        @else
                            <span style="color:#aaa;">Không có ảnh</span>
                        @endif
                    </td>

                    <td>{{ $food->name }}</td>
                    <td>{{ number_format($food->price) }} đ</td>
                    <td>{{ $food->total }}</td>

                    <td>
                        <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="{{ route('admin.foods.delete', $food->id) }}" method="POST"
                              style="display:inline-block;"
                              onsubmit="return confirm('Xóa món này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:#888;">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

@endsection
