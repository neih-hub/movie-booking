@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-users"></i> Danh sách người dùng
            </h2>
        </div>

        <!-- Search and Filter -->
        <form method="GET" action="{{ route('admin.users.list') }}" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc email..."
                value="{{ request('search') }}">

            <select name="role" class="form-select" style="width: 200px;">
                <option value="">Tất cả vai trò</option>
                <option value="0" {{ request('role') === '0' ? 'selected' : '' }}>Admin</option>
                <option value="1" {{ request('role') === '1' ? 'selected' : '' }}>User</option>
            </select>

            <select name="status" class="form-select" style="width: 200px;">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Bị khóa</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>

        <!-- Users Table -->
        @if($users->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>
                                    @if($user->avatar)
                                        <img src="/{{ $user->avatar }}" alt="{{ $user->name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                            alt="{{ $user->name }}">
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 0)
                                        <span class="badge badge-danger">Admin</span>
                                    @else
                                        <span class="badge badge-info">User</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status == 1)
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-danger">Bị khóa</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn {{ $user->status == 1 ? 'btn-warning' : 'btn-success' }} btn-sm"
                                            title="{{ $user->status == 1 ? 'Khóa' : 'Mở khóa' }}">
                                            <i class="fas fa-{{ $user->status == 1 ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>

                                    @if($user->id != auth()->id())
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                            style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $users->links() }}
            </div>
        @else
            <p style="text-align: center; color: #64748b; padding: 2rem;">Không tìm thấy người dùng nào</p>
        @endif
    </div>
@endsection