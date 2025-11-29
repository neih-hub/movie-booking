@extends('layouts.main')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-4">

      <div class="card shadow-sm">
        <div class="card-header text-center fw-bold">
          Đăng ký tài khoản
        </div>

        <div class="card-body">

          {{-- Hiển thị lỗi --}}
          @if($errors->any())
          <div class="alert alert-danger py-2">
            @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
            @endforeach
          </div>
          @endif

          <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Xác nhận mật khẩu</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Đăng ký</button>

            <div class="text-center mt-3">
              <a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection