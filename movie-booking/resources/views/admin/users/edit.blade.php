@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')
@section('page-title', 'Chỉnh sửa người dùng')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-edit"></i> Chỉnh sửa thông tin người dùng
            </h2>
            <a href="{{ route('admin.users.list') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Tên <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò <span style="color: red;">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>User</option>
                        <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái <span style="color: red;">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Bị khóa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">Chọn giới tính</option>
                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" class="form-control"
                        value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Avatar</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
                @if($user->avatar)
                    <div style="margin-top: 1rem;">
                        <img src="/{{ $user->avatar }}" alt="Current avatar"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                    </div>
                @endif
            </div>

            <div style="border-top: 2px solid #f1f5f9; padding-top: 1.5rem; margin-top: 1.5rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.125rem;">Đổi mật khẩu (để trống nếu không muốn đổi)</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="6">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
                <a href="{{ route('admin.users.list') }}" class="btn btn-danger">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
@endsection