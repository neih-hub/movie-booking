@extends('layouts.main')

@section('content')
<div class="container py-4">

  <h2 class="fw-bold mb-3">🎛 Admin Panel</h2>
  <p>Xin chào, {{ Auth::user()->name }} (Admin)</p>

  <div class="row">

    <div class="col-md-3 mb-3">
      <a href="{{ route('admin.movies.list') }}" class="btn btn-primary w-100 py-3">🎬 Quản lý phim</a>

    </div>

    <div class="col-md-3 mb-3">
      <a href="#" class="btn btn-secondary w-100 py-3">
        🎦 Quản lý rạp
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="#" class="btn btn-success w-100 py-3">
        🕒 Quản lý suất chiếu
      </a>
    </div>

    <div class="col-md-3 mb-3">
      <a href="{{ route('admin.users.list') }}" class="btn btn-danger w-100 py-3">👤 Quản lý người dùng</a>
    </div>

  </div>

</div>
@endsection