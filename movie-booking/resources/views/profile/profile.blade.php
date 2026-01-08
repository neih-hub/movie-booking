@extends('layouts.main')

@section('content')

<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="profile-wrapper">
  <div class="container profile-container">

    <div class="row g-4">

      
      <div class="col-md-3">

        <div class="avatar-card">

          <div class="avatar-wrapper">
            <img src="{{ $user->avatar ? asset($user->avatar) : '/images/default-avatar.png' }}"
                 class="avatar-image"
                 alt="Avatar">

            
            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <label class="avatar-upload-btn">
                <i class="bi bi-camera"></i>
                <input type="file" name="avatar" hidden onchange="this.form.submit()">
              </label>
            </form>
          </div>

          <h4 class="user-name">{{ $user->name ?? 'Chưa có tên' }}</h4>
          <p class="user-email">{{ $user->email }}</p>

        </div>

      </div>

      
      <div class="col-md-9">

        <div class="content-card">

          
          <ul class="nav nav-tabs custom-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link custom-tab active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
                Thông tin cá nhân
              </a>
            </li>

            

            <li class="nav-item" role="presentation">
              <a class="nav-link custom-tab" id="history-tab" data-bs-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">
                Lịch sử giao dịch
              </a>
            </li>

            <li class="nav-item" role="presentation">
              <a class="nav-link custom-tab" id="saved-tab" data-bs-toggle="tab" href="#saved" role="tab" aria-controls="saved" aria-selected="false">
                Bộ phim đã lưu
              </a>
            </li>
          </ul>

          <div class="tab-content" id="profileTabContent">

            
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">

              <h5 class="form-section-title">Cập nhật thông tin</h5>

              @if(session('success'))
                <div class="alert-custom-success">{{ session('success') }}</div>
              @endif

              <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label class="form-label-custom">Họ và tên</label>
                      <input type="text" name="name" class="form-input-custom" value="{{ old('name', $user->name) }}">
                      @error('name') <small class="error-text">{{ $message }}</small> @enderror
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label class="form-label-custom">Ngày sinh</label>
                      <input type="date" name="birthday" class="form-input-custom"
                             value="{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '' }}">
                      @error('birthday') <small class="error-text">{{ $message }}</small> @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label class="form-label-custom">Email</label>
                      <input type="email" class="form-input-custom" disabled value="{{ $user->email }}">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group-custom">
                      <label class="form-label-custom">Số điện thoại</label>
                      <input type="text" name="phone" class="form-input-custom" value="{{ old('phone', $user->phone) }}">
                      @error('phone') <small class="error-text">{{ $message }}</small> @enderror
                    </div>
                  </div>
                </div>

                <div class="form-group-custom">
                  <label class="form-label-custom">Địa chỉ</label>
                  <input type="text" name="address" class="form-input-custom" value="{{ old('address', $user->address) }}">
                  @error('address') <small class="error-text">{{ $message }}</small> @enderror
                </div>

                <div class="form-group-custom">
                  <label class="form-label-custom">Giới tính</label>
                  <select name="gender" class="form-select-custom">
                    <option value="">Chọn giới tính</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                  </select>
                  @error('gender') <small class="error-text">{{ $message }}</small> @enderror
                </div>

                <button class="btn-submit-custom">Cập nhật</button>
              </form>

            </div>

            
            <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
              <h5 class="form-section-title">Thông báo</h5>
              <div class="empty-state">
                <div class="empty-state-icon">🔔</div>
                <p class="empty-state-text">Hiện tại không có thông báo nào.</p>
              </div>
            </div>

            
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
              <h5 class="form-section-title">Lịch sử đặt vé</h5>
              
              @if(isset($bookings) && $bookings->count() > 0)
                <div style="overflow-x: auto;">
                  @foreach($bookings as $booking)
                    <div style="background: #f8fafc; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; border-left: 4px solid {{ $booking->status == 1 ? '#10b981' : '#ef4444' }};">
                      <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                          <h6 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                            {{ $booking->showtime->movie->title }}
                          </h6>
                          <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
                            Mã đặt vé: #{{ $booking->id }}
                          </p>
                        </div>
                        <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $booking->status == 1 ? 'background: #d1fae5; color: #065f46;' : 'background: #fee2e2; color: #991b1b;' }}">
                          {{ $booking->status == 1 ? 'Đã thanh toán' : 'Đã hủy' }}
                        </span>
                      </div>

                      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <div>
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.25rem;">Rạp chiếu</p>
                          <p style="color: #1e293b; font-weight: 600; margin: 0;">{{ $booking->showtime->room->cinema->name }}</p>
                        </div>
                        <div>
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.25rem;">Phòng</p>
                          <p style="color: #1e293b; font-weight: 600; margin: 0;">{{ $booking->showtime->room->name }}</p>
                        </div>
                        <div>
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.25rem;">Thời gian</p>
                          <p style="color: #1e293b; font-weight: 600; margin: 0;">
                            {{ \Carbon\Carbon::parse($booking->showtime->date_start)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }}
                          </p>
                        </div>
                        <div>
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.25rem;">Tổng tiền</p>
                          <p style="color: #3b82f6; font-weight: 700; font-size: 1.125rem; margin: 0;">
                            {{ number_format($booking->total_price) }} đ
                          </p>
                        </div>
                      </div>

                      @if($booking->bookingSeats->count() > 0)
                        <div style="margin-bottom: 0.75rem;">
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.5rem;">Ghế đã đặt:</p>
                          <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @foreach($booking->bookingSeats as $bookingSeat)
                              <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                {{ $bookingSeat->seat->seat_number }}
                              </span>
                            @endforeach
                          </div>
                        </div>
                      @endif

                      @if($booking->bookingFoods->count() > 0)
                        <div>
                          <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 0.5rem;">Thức ăn & đồ uống:</p>
                          <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach($booking->bookingFoods as $bookingFood)
                              <li style="color: #64748b; font-size: 0.875rem;">
                                {{ $bookingFood->food->name }} x{{ $bookingFood->quantity }} 
                                ({{ number_format($bookingFood->price * $bookingFood->quantity) }} đ)
                              </li>
                            @endforeach
                          </ul>
                        </div>
                      @endif

                      <p style="color: #94a3b8; font-size: 0.75rem; margin-top: 1rem; margin-bottom: 0;">
                        Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}
                      </p>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="empty-state">
                  <div class="empty-state-icon">📜</div>
                  <p class="empty-state-text">Bạn chưa có giao dịch nào.</p>
                </div>
              @endif
            </div>

            
            <div class="tab-pane fade" id="saved" role="tabpanel" aria-labelledby="saved-tab">
              <h5 class="form-section-title">Phim đã lưu</h5>
              <div class="empty-state">
                <div class="empty-state-icon">🎬</div>
                <p class="empty-state-text">Bạn chưa lưu phim nào.</p>
              </div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>

@endsection
