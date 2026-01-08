@extends('layouts.admin')

@section('title', 'Quản lí phòng')
@section('page-title', 'Quản lí phòng')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/rooms-manage.css') }}">
@endpush

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-door-open"></i> Quản lí phòng chiếu
            </h2>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Thêm phòng mới
            </a>
        </div>

        
        <div class="cinema-selector">
            <label for="cinema_select">
                <i class="bi bi-building"></i>
                Chọn rạp chiếu để xem danh sách phòng
            </label>
            <select id="cinema_select" class="form-select">
                <option value="">-- Chọn rạp chiếu --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>

        
        <div id="rooms_container" style="display: none;">
            <div class="rooms-section">
                <div class="rooms-section-title">
                    <i class="bi bi-grid-3x3"></i>
                    <h3>Danh sách phòng chiếu</h3>
                </div>
                <div id="rooms_grid" class="rooms-grid">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // gửi dữ liệu cinema cho js
    window.cinemasData = @json($cinemas);
</script>
<script src="{{ asset('js/rooms-manage.js') }}"></script>
@endpush