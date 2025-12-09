@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth-register.css') }}">

<div class="register-wrapper">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="col-md-5 col-lg-4">
        
        <div class="register-card">
          
          <div class="register-header">
            <h1 class="register-title">🎬 Cinema</h1>
            <p class="register-subtitle">Tạo tài khoản mới</p>
          </div>

          {{-- Hiển thị lỗi --}}
          @if($errors->any())
          <div class="alert-custom alert-danger">
            @foreach($errors->all() as $err)
            <div>• {{ $err }}</div>
            @endforeach
          </div>
          @endif

          <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
              <input 
                type="email" 
                name="email" 
                class="form-input" 
                placeholder=" "
                required
                autocomplete="email"
              >
              <label class="form-label">Email</label>
              <span class="input-icon">📧</span>
            </div>

            <div class="form-group">
              <input 
                type="password" 
                name="password" 
                class="form-input" 
                placeholder=" "
                required
                autocomplete="new-password"
              >
              <label class="form-label">Mật khẩu</label>
              <span class="input-icon">🔒</span>
            </div>

            <div class="form-group">
              <input 
                type="password" 
                name="password_confirmation" 
                class="form-input" 
                placeholder=" "
                required
                autocomplete="new-password"
              >
              <label class="form-label">Xác nhận mật khẩu</label>
              <span class="input-icon">✓</span>
            </div>

            <button type="submit" class="btn-register">
              Đăng ký
            </button>

            <div class="login-link">
              <a href="{{ route('login') }}">Đã có tài khoản? Đăng nhập ngay</a>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>
</div>
@endsection