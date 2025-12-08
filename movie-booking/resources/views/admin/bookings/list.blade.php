@extends('layouts.admin')

@section('title', 'Quản lý đặt vé')
@section('page-title', 'Quản lý đặt vé')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-ticket-alt"></i> Danh sách đặt vé
            </h2>
        </div>

        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.bookings.list') }}" class="search-bar">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc email người dùng..."
                value="{{ request('search') }}">

            <input type="date" name="date" class="form-control" value="{{ request('date') }}">

            <select name="status" class="form-select" style="width: 200px;">
                <option value="">Tất cả trạng thái</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã hủy</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>

        @if($bookings->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Phim</th>
                            <th>Rạp</th>
                            <th>Thời gian chiếu</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                                <td>{{ $booking->showtime->room->cinema->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->showtime->date_start . ' ' . $booking->showtime->start_time)->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($booking->total_price ?? 0) }} VNĐ</td>
                                <td>
                                    @if($booking->status == 1)
                                        <span class="badge badge-success">Đã thanh toán</span>
                                    @else
                                        <span class="badge badge-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Toggle Cancel/Restore button --}}
                                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('{{ $booking->status == 1 ? 'Bạn có chắc muốn hủy vé này?' : 'Bạn có chắc muốn khôi phục vé này?' }}')">
                                        @csrf
                                        @if($booking->status == 1)
                                            <button type="submit" class="btn btn-warning btn-sm" title="Hủy vé">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success btn-sm" title="Khôi phục vé">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đặt vé này?')">
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
                {{ $bookings->links() }}
            </div>
        @else
            <p style="text-align: center; color: #64748b; padding: 2rem;">Không tìm thấy đặt vé nào</p>
        @endif
    </div>
@endsection