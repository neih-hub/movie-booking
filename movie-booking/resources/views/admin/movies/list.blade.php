@extends('layouts.main')

@section('content')
<div class="container py-4">

  <h3 class="fw-bold mb-3">Danh sách phim</h3>

  <a href="{{ route('admin.movies.create') }}" class="btn btn-success mb-3">
    + Thêm phim
  </a>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <tr>
      <th>ID</th>
      <th>Poster</th>
      <th>Title</th>
      <th>Thời lượng</th>
      <th>Hành động</th>
    </tr>

    @foreach($movies as $m)
    <tr>
      <td>{{ $m->id }}</td>
      <td><img src="/{{ $m->poster }}" width="60"></td>
      <td>{{ $m->title }}</td>
      <td>{{ $m->duration }}</td>
      <td>
        <a href="{{ route('admin.movies.edit', $m->id) }}" class="btn btn-warning btn-sm">Sửa</a>

        <form action="{{ route('admin.movies.delete', $m->id) }}" method="POST" style="display:inline-block;">
          @csrf
          <button onclick="return confirm('Xóa?')" class="btn btn-danger btn-sm">
            Xóa
          </button>
        </form>
      </td>
    </tr>
    @endforeach

  </table>

</div>
@endsection