@extends('layouts.main')

@section('content')
<div class="container py-4">

  <h3 class="fw-bold mb-3">Thêm phim</h3>

  <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Tiêu đề</label>
    <input type="text" name="title" class="form-control mb-3">

    <label>Mô tả</label>
    <textarea name="description" class="form-control mb-3"></textarea>

    <label>Thời lượng</label>
    <input type="number" name="duration" class="form-control mb-3">

    <label>Thể loại</label>
    <input type="text" name="genre" class="form-control mb-3">

    <label>Ngày công chiếu</label>
    <input type="date" name="release_date" class="form-control mb-3">

    <label>Poster</label>
    <input type="file" name="poster" class="form-control mb-3">

    <button class="btn btn-primary">Lưu</button>
  </form>

</div>
@endsection