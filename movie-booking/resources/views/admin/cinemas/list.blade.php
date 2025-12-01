@extends('layouts.admin')

@section('title', 'Quản lý rạp chiếu')
@section('page-title', 'Quản lý rạp chiếu')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-building"></i> Danh sách rạp chiếu
            </h2>
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm rạp mới
            </a>
        </div>

        @if($cinemas->count() > 0)
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên rạp</th>
                            <th>Địa chỉ</th>
                          
                        
                            <th>Số phòng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cinemas as $cinema)
                            <tr>
                                <td>#{{ $cinema->id }}</td>
                                <td>{{ $cinema->name }}</td>
                                <td>{{ $cinema->address }}</td>
                                
                               
                                <td>
                                    <span class="badge badge-info">{{ $cinema->rooms_count ?? 0 }} phòng</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.cinemas.delete', $cinema->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa rạp này?')">
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
                {{ $cinemas->links() }}
            </div>
        @else
            <p style="text-align: center; color: #64748b; padding: 2rem;">Chưa có rạp chiếu nào</p>
        @endif
    </div>
@endsection