@extends('layouts.main')

@section('content')

<div class="container py-4">
  <h3>Thêm phim mới</h3>

  <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label class="form-label">Tiêu đề</label>
    <input name="title" class="form-control mb-3" required>

    <label class="form-label">Thời lượng</label>
    <input name="duration" type="number" class="form-control mb-3">

    <label class="form-label">Ngày phát hành</label>
    <input name="release_date" type="date" class="form-control mb-3">

    <label class="form-label">Poster</label>
    <input name="poster" type="file" class="form-control mb-3">

    <button class="btn btn-success">Lưu phim</button>
  </form>
</div>

@endsection