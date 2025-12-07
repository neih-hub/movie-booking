@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth-login.css') }}">

<div class="login-wrapper">
  <div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
      <div class="col-md-5 col-lg-4">
        
        <div class="login-card">
          
          <div class="login-header">
            <h1 class="login-title">🎬 Cinema</h1>
            <p class="login-subtitle">Đăng nhập để tiếp tục</p>
          </div>

          {{-- Hiển thị lỗi --}}
          @if($errors->any())
          <div class="alert-custom alert-danger">
            @foreach($errors->all() as $err)
            <div>• {{ $err }}</div>
            @endforeach
          </div>
          @endif

          {{-- đăng kí thành công --}}
          @if(session('success'))
          <div class="alert-custom alert-success">
            {{ session('success') }}
          </div>
          @endif

          <form action="{{ route('login') }}" method="POST">
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
                autocomplete="current-password"
              >
              <label class="form-label">Mật khẩu</label>
              <span class="input-icon">🔒</span>
            </div>

            <button type="submit" class="btn-login">
              Đăng nhập
            </button>

            <div class="register-link">
              <a href="{{ route('register') }}">Chưa có tài khoản? Đăng ký ngay</a>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>
</div>
@endsection