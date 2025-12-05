@extends('layouts.admin')

@section('title', 'Quản lý suất chiếu')
@section('page-title', 'Quản lý suất chiếu')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-clock"></i> Danh sách suất chiếu
            </h2>
            <a href="{{ route('admin.showtimes.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm suất chiếu
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.showtimes.list') }}" class="search-bar">
            <select name="movie_id" class="form-select">
                <option value="">Tất cả phim</option>
                @foreach($movies as $movie)
                    <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                        {{ $movie->title }}
                    </option>
                @endforeach
            </select>

            <select name="cinema_id" class="form-select">
                <option value="">Tất cả rạp</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}" {{ request('cinema_id') == $cinema->id ? 'selected' : '' }}>
                        {{ $cinema->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date" class="form-control" value="{{ request('date') }}">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Lọc
            </button>
        </form>

        @if($showtimes->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phim</th>
                            <th>Rạp</th>
                            <th>Phòng</th>
                            <th>Thời gian</th>
                            <th>Giá vé</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($showtimes as $showtime)
                            <tr>
                                <td>#{{ $showtime->id }}</td>
                                <td>{{ $showtime->movie->title ?? 'N/A' }}</td>
                                <td>{{ $showtime->room->cinema->name ?? 'N/A' }}</td>
                                <td>{{ $showtime->room->name ?? 'N/A' }}</td>
                                <td>{{ $showtime->date_start }} {{ $showtime->start_time }}</td>
                                <td>{{ number_format($showtime->price ?? 0) }} VNĐ</td>
                                <td>
                                    <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.showtimes.delete', $showtime->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa suất chiếu này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $showtimes->links() }}
            </div>
        @else
            <p style="text-align: center; color: #64748b; padding: 2rem;">Không tìm thấy suất chiếu nào</p>
        @endif
    </div>
@endsection