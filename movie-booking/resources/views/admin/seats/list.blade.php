@extends('layouts.admin')

@section('title', 'Quản lý ghế ngồi')
@section('page-title', 'Quản lý ghế ngồi')
@section('page-subtitle', 'Danh sách ghế ngồi theo rạp và phòng chiếu')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-couch"></i> Danh sách ghế ngồi
            </h2>
            <a href="{{ route('admin.seats.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Thêm ghế mới
            </a>
        </div>

        @foreach($cinemas as $cinema)
            <div class="mb-4">
                <h4 class="mb-3">
                    <i class="fas fa-building"></i> {{ $cinema->name }}
                </h4>

                @if($cinema->rooms->count() > 0)
                    @foreach($cinema->rooms as $room)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-door-open"></i> {{ $room->name }}</strong>
                                <span class="badge bg-primary ms-2">{{ $room->seats->count() }} ghế</span>
                            </div>
                            <div class="card-body">
                                @if($room->seats->count() > 0)
                                    <div class="row g-2">
                                        @foreach($room->seats as $seat)
                                            <div class="col-auto">
                                                <div class="seat-item {{ $seat->type }}">
                                                    {{ $seat->seat_number }}
                                                    <form action="{{ route('admin.seats.delete', $seat->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn-delete" onclick="return confirm('Xóa ghế này?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Chưa có ghế nào</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Chưa có phòng chiếu nào</p>
                @endif
            </div>
        @endforeach

        @if($cinemas->count() == 0)
            <p class="text-center text-muted py-4">
                <i class="fas fa-couch fa-3x d-block mb-3"></i>
                Chưa có rạp chiếu nào
            </p>
        @endif
    </div>

@endsection

@section('styles')
    <style>
        .seat-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            position: relative;
            background: #f8f9fa;
        }

        .seat-item.vip {
            background: linear-gradient(135deg, #f7941e, #e67e22);
            color: white;
            border-color: #f7941e;
        }

        .seat-item .btn-delete {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            border: none;
            font-size: 0.625rem;
            cursor: pointer;
            display: none;
        }

        .seat-item:hover .btn-delete {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection