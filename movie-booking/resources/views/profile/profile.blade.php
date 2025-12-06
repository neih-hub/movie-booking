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

        {{-- Form upload avatar --}}
        <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <label class="btn btn-sm btn-light shadow position-absolute"
                 style="bottom: 0; right: 0; border-radius: 50%; cursor: pointer;">
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
      <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
            Thông tin cá nhân
          </a>
        </li>

        <li class="nav-item" role="presentation">
          <a class="nav-link" id="notifications-tab" data-bs-toggle="tab" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">
            Thông báo
          </a>
        </li>

        <li class="nav-item" role="presentation">
          <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
            Lịch sử giao dịch
          </a>
        </li>

        <li class="nav-item" role="presentation">
          <a class="nav-link" id="saved-tab" data-bs-toggle="tab" href="#saved" role="tab" aria-controls="saved" aria-selected="false">
            Bộ phim đã lưu
          </a>
        </li>
      </ul>

      <div class="tab-content" id="profileTabContent">

        {{-- ====================== TAB: THÔNG TIN CÁ NHÂN ====================== --}}
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">

          <h5 class="mb-3 fw-bold">Cập nhật thông tin</h5>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Họ và tên</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Ngày sinh</label>
                <input type="date" name="birthday" class="form-control"
                       value="{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '' }}">
                @error('birthday') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" disabled value="{{ $user->email }}">
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Địa chỉ</label>
              <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
              @error('address') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="">Chọn giới tính</option>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
              </select>
              @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-primary px-4">Cập nhật</button>
          </form>

        </div>

        {{-- ====================== TAB: THÔNG BÁO ====================== --}}
        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
          <h5 class="fw-bold">Thông báo</h5>
          <p class="text-muted">Hiện tại không có thông báo nào.</p>
        </div>

        {{-- ====================== TAB: LỊCH SỬ GIAO DỊCH ====================== --}}
        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
          <h5 class="fw-bold mb-3">Lịch sử giao dịch</h5>
          <p class="text-muted">Bạn chưa có giao dịch nào.</p>
        </div>

        {{-- ====================== TAB: BỘ PHIM ĐÃ LƯU ====================== --}}
        <div class="tab-pane fade" id="saved" role="tabpanel" aria-labelledby="saved-tab">
          <h5 class="fw-bold mb-3">Phim đã lưu</h5>
          <p class="text-muted">Bạn chưa lưu phim nào.</p>
        </div>

      </div>

    </div>

  </div>

</div>

@endsection
