@extends('layouts.main')

@section('content')

<div class="container py-4">
  <h3>Sửa phim</h3>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label class="form-label">Tiêu đề</label>
    <input name="title" class="form-control mb-3" value="{{ $movie->title }}" required>

    <label class="form-label">Thời lượng</label>
    <input name="duration" type="number" class="form-control mb-3" value="{{ $movie->duration }}">

    <label class="form-label">Ngày phát hành</label>
    <input name="release_date" type="date" class="form-control mb-3" value="{{ $movie->release_date }}">

    <label>Poster hiện tại</label><br>
    <img src="{{ asset($movie->poster) }}" width="120" class="mb-3"><br>

    <label class="form-label">Đổi poster</label>
    <input type="file" name="poster" class="form-control mb-3">

    <button class="btn btn-primary">Cập nhật</button>
  </form>
</div>

@endsection