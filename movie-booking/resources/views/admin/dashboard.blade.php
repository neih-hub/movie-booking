@extends('layouts.main')

@section('content')
<div class="container py-4">
  <h2 class="fw-bold">🎬 Admin Panel</h2>
  <p>Xin chào, {{ Auth::user()->name }} (Admin)</p>

  <div class="row mt-4">

    <div class="col-md-3 mb-3">
      <a href="/admin/movies" class="btn btn-primary w-100 py-3">
        Quản lý phim
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="/admin/cinemas" class="btn btn-primary w-100 py-3">
        Quản lý rạp
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="/admin/showtimes" class="btn btn-primary w-100 py-3">
        Quản lý suất chiếu
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="/admin/users" class="btn btn-primary w-100 py-3">
        Quản lý người dùng
      </a>
    </div>

  </div>

</div>
@endsection