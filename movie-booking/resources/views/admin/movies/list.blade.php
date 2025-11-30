@extends('layouts.main')

@section('content')

<div class="container py-4">
  <h3 class="mb-3">Danh sách phim</h3>

  <a href="{{ route('admin.movies.create') }}" class="btn btn-primary mb-3">+ Thêm phim</a>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Poster</th>
        <th>Tiêu đề</th>
        <th>Thời lượng</th>
        <th>Ngày phát hành</th>
        <th>Tùy chọn</th>
      </tr>
    </thead>

    <tbody>
      @foreach($movies as $m)
      <tr>
        <td>{{ $m->id }}</td>
        <td><img src="{{ asset($m->poster) }}" width="70"></td>
        <td>{{ $m->title }}</td>
        <td>{{ $m->duration }}</td>
        <td>{{ $m->release_date }}</td>
        <td>
          <a class="btn btn-warning btn-sm" href="{{ route('admin.movies.edit',$m->id) }}">Sửa</a>

          <form action="{{ route('admin.movies.delete',$m->id) }}" method="POST" class="d-inline">
            @csrf
            <button onclick="return confirm('Xóa phim này?')" class="btn btn-danger btn-sm">
              Xóa
            </button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection