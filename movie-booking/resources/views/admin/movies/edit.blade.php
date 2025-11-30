@extends('layouts.main')

@section('content')
<div class="container py-4">

  <h3 class="fw-bold mb-3">Sửa phim</h3>

  <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Tiêu đề</label>
    <input type="text" name="title" value="{{ $movie->title }}" class="form-control mb-3">

    <label>Mô tả</label>
    <textarea name="description" class="form-control mb-3">{{ $movie->description }}</textarea>

    <label>Thời lượng</label>
    <input type="number" name="duration" value="{{ $movie->duration }}" class="form-control mb-3">

    <label>Thể loại</label>
    <input type="text" name="genre" value="{{ $movie->genre }}" class="form-control mb-3">

    <label>Ngày công chiếu</label>
    <input type="date" name="release_date" value="{{ $movie->release_date }}" class="form-control mb-3">

    <label>Poster</label>
    <input type="file" name="poster" class="form-control mb-3">

    <img src="/{{ $movie->poster }}" width="120" class="mb-3">

    <button class="btn btn-primary">Cập nhật</button>
  </form>

</div>
@endsection