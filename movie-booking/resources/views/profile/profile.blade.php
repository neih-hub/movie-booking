@extends('layouts.main')

@section('content')

<div class="container py-5">

  <div class="row">

    {{-- ====================== Cột trái (Avatar + Tên) ====================== --}}
    <div class="col-md-3 text-center">

      <div class="position-relative d-inline-block">
        <img src="{{ $user->avatar ? asset($user->avatar) : '/images/default-avatar.png' }}"
             class="rounded-circle"
             width="140" height="140"
             style="object-fit: cover; border: 4px solid #ddd;">

        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <label class="btn btn-sm btn-light shadow position-absolute"
                 style="bottom: 0; right: 0; border-radius: 50%;">
            <i class="bi bi-camera"></i>
            <input type="file" name="avatar" hidden onchange="this.form.submit()">
          </label>
        </form>
      </div>

      <h4 class="mt-3">{{ $user->name ?? 'Chưa có tên' }}</h4>
      <p class="text-muted">{{ $user->email }}</p>

      <hr>

    </div>

    {{-- ====================== Cột phải (Tabs) ====================== --}}
    <div class="col-md-9">

      {{-- TAB MENU --}}
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info">
            Thông tin cá nhân
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notifications">
            Thông báo
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#history">
            Lịch sử giao dịch
          </button>
        </li>

        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#saved">
            Bộ phim đã lưu
          </button>
        </li>
      </ul>

      <div class="tab-content">

        {{-- ====================== TAB: THÔNG TIN CÁ NHÂN ====================== --}}
        <div class="tab-pane fade show active" id="info">

          <h5 class="mb-3 fw-bold">Cập nhật thông tin</h5>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Họ và tên</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Ngày sinh</label>
                <input type="date" name="birthday" class="form-control"
                       value="{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '' }}">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" disabled value="{{ $user->email }}">
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Địa chỉ</label>
              <input type="text" name="address" class="form-control" value="{{ $user->address }}">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="">Chọn giới tính</option>
                <option value="male" {{ $user->gender=='male'?'selected':'' }}>Nam</option>
                <option value="female" {{ $user->gender=='female'?'selected':'' }}>Nữ</option>
                <option value="other" {{ $user->gender=='other'?'selected':'' }}>Khác</option>
              </select>
            </div>

            <button class="btn btn-primary px-4">Cập nhật</button>
          </form>

        </div>

        {{-- ====================== TAB: THÔNG BÁO ====================== --}}
        <div class="tab-pane fade" id="notifications">
          <h5>Thông báo</h5>
          <p class="text-muted">Hiện tại không có thông báo nào.</p>
        </div>

        {{-- ====================== TAB: LỊCH SỬ GIAO DỊCH ====================== --}}
        <div class="tab-pane fade" id="history">
          <h5 class="mb-3 fw-bold">Lịch sử giao dịch</h5>
          <p class="text-muted">Bạn chưa có giao dịch nào.</p>
        </div>

        {{-- ====================== TAB: BỘ PHIM ĐÃ LƯU ====================== --}}
        <div class="tab-pane fade" id="saved">
          <h5 class="mb-3 fw-bold">Phim đã lưu</h5>
          <p class="text-muted">Bạn chưa lưu phim nào.</p>
        </div>

      </div>

    </div>

  </div>

</div>

@endsection
