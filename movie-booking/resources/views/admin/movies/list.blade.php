@extends('layouts.admin')

@section('title', 'Quản lý phim')
@section('page-title', 'Quản lý phim')

@section('content')
  <div class="content-card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="bi bi-film"></i> Danh sách phim
      </h2>
      <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm phim mới
      </a>
    </div>

    @if($movies->count() > 0)
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Poster</th>
              <th>Tiêu đề</th>
              <th>Thời lượng</th>
              <th>Ngày phát hành</th>
              <th>Hành động</th>
            </tr>
          </thead>

          <tbody>
            @foreach($movies as $m)
              <tr>
                <td>#{{ $m->id }}</td>
                <td><img src="{{ asset($m->poster) }}" alt="{{ $m->title }}"></td>
                <td><strong>{{ $m->title }}</strong></td>
                <td>{{ $m->duration }} phút</td>
                <td>{{ $m->release_date }}</td>
                <td>
                  <a class="btn btn-warning btn-sm" href="{{ route('admin.movies.edit', $m->id) }}">
                    <i class="bi bi-pencil"></i> Sửa
                  </a>

                  <form action="{{ route('admin.movies.delete', $m->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button onclick="return confirm('Bạn có chắc muốn xóa phim này?')" class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i> Xóa
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p style="text-align: center; color: #64748b; padding: 2rem;">
        <i class="bi bi-film" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
        Chưa có phim nào
      </p>
    @endif
  </div>
@endsection