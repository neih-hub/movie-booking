@extends('layouts.admin')

{{-- ========== LOAD CSS ========== --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/genre.css') }}">
@endpush

@section('title', 'Sửa phim')
@section('page-title', 'Sửa phim')

@section('content')

<div class="content-card">

    {{-- ================= HEADER ================= --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="card-title">
            <i class="fas fa-edit"></i> Sửa phim: {{ $movie->title }}
        </h2>

        <a href="{{ route('admin.movies.list') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- ================= FORM ================= --}}
    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data" class="movie-form">
        @csrf

        {{-- Tiêu đề phim --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-heading"></i>
                <h3>Thông tin cơ bản</h3>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-film"></i> Tiêu đề phim 
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="title" class="form-control" placeholder="Nhập tên phim..." value="{{ $movie->title }}" required>
            </div>
        </div>

        {{-- Thời lượng --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-clock"></i>
                <h3>Thời lượng</h3>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-stopwatch"></i> Thời lượng (phút)
                </label>
                <input type="number" name="duration" class="form-control" placeholder="Ví dụ: 120" min="1" value="{{ $movie->duration }}">
            </div>
        </div>

        {{-- Mô tả --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-align-left"></i>
                <h3>Mô tả phim</h3>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-file-alt"></i> Nội dung mô tả
                </label>
                <textarea name="description" class="form-control" rows="5" placeholder="Nhập mô tả chi tiết về phim...">{{ $movie->description }}</textarea>
            </div>
        </div>

        {{-- Thể loại --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-tags"></i>
                <h3>Thể loại phim</h3>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-list"></i> Chọn thể loại 
                    <span class="badge bg-info">Tối đa 4</span>
                </label>

                @php
                    $genres = [
                        'Hành động','Phiêu lưu','Khoa học viễn tưởng','Kinh dị',
                        'Tâm lý','Tình cảm','Hài','Hoạt hình','Gia đình',
                        'Chiến tranh','Hình sự','Thể thao','Nhạc kịch',
                        'Viễn Tây','Giả tưởng','Bí ẩn','Tài liệu',
                        'Lịch sử','Phiêu lưu - Hành động','Anime'
                    ];
                    
                    $selectedGenres = $movie->genre;
                    if (is_string($selectedGenres)) {
                        $selectedGenres = explode(',', $selectedGenres);
                        $selectedGenres = array_map('trim', $selectedGenres);
                    }
                    $selectedGenres = $selectedGenres ?? [];
                @endphp

                <div id="genre-wrapper" class="genre-container">
                    @foreach($genres as $g)
                        <button type="button" class="genre-btn {{ in_array($g, $selectedGenres) ? 'active' : '' }}" data-value="{{ $g }}">
                            <i class="fas fa-tag"></i>
                            {{ $g }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden" name="genre" id="genreInput" value="{{ is_array($selectedGenres) ? implode(', ', $selectedGenres) : $selectedGenres }}">
            </div>
        </div>

        {{-- Ngày công chiếu & Poster --}}
        <div class="form-section">
            <div class="section-header">
                <i class="fas fa-calendar-alt"></i>
                <h3>Ngày công chiếu & Poster</h3>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-day"></i> Ngày công chiếu
                        </label>
                        <input type="date" name="release_date" class="form-control" value="{{ $movie->release_date }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-image"></i> Poster phim mới (nếu có)
                        </label>
                        <input type="file" name="poster" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            {{-- Poster hiện tại --}}
            @if($movie->poster)
                <div class="mt-3">
                    <label class="form-label">
                        <i class="fas fa-image"></i> Poster hiện tại
                    </label>
                    <div style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 1rem; display: inline-block;">
                        <img src="/{{ $movie->poster }}" alt="{{ $movie->title }}" style="max-width: 200px; border-radius: 4px;">
                    </div>
                </div>
            @endif
        </div>

        {{-- ================= BUTTONS ================= --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Cập nhật phim
            </button>

            <a href="{{ route('admin.movies.list') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Hủy bỏ
            </a>
        </div>

    </form>
</div>

{{-- ========== LOAD JS ========== --}}
@push('scripts')
<script>
// Preload selected genres for edit mode
window.selectedGenres = @json($selectedGenres);
</script>
<script src="{{ asset('js/genre.js') }}"></script>
@endpush

@endsection
