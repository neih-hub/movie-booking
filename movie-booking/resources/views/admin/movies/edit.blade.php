@extends('layouts.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/genre.css') }}">
@endpush

@section('content')

<div class="container py-4">

    <h3 class="fw-bold mb-3">Sửa phim: {{ $movie->title }}</h3>

    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tiêu đề --}}
        <label>Tiêu đề</label>
        <input type="text" name="title" value="{{ $movie->title }}" class="form-control mb-3" required>

        {{-- Mô tả --}}
        <label>Mô tả</label>
        <textarea name="description" class="form-control mb-3">{{ $movie->description }}</textarea>

        {{-- Thời lượng --}}
        <label>Thời lượng (phút)</label>
        <input type="number" name="duration" value="{{ $movie->duration }}" class="form-control mb-3">

        {{-- Thể loại --}}
        <label>Thể loại (chọn tối đa 4)</label>

        @php
            $genres = [
                'Hành động', 'Phiêu lưu', 'Khoa học viễn tưởng', 'Kinh dị',
                'Tâm lý', 'Tình cảm', 'Hài', 'Hoạt hình', 'Gia đình',
                'Chiến tranh', 'Hình sự', 'Thể thao', 'Nhạc kịch',
                'Viễn Tây', 'Giả tưởng', 'Bí ẩn', 'Tài liệu',
                'Lịch sử', 'Phiêu lưu - Hành động', 'Anime'
            ];

            $selectedGenres = $movie->genre ?? [];
        @endphp

        <div id="genre-wrapper" class="d-flex flex-wrap gap-2 mb-3">
            @foreach($genres as $g)
                <button type="button"
                    class="genre-btn btn btn-outline-secondary @if(in_array($g, $selectedGenres)) active @endif"
                    data-value="{{ $g }}">
                    {{ $g }}
                </button>
            @endforeach
        </div>

        <input type="hidden" name="genre" id="genreInput">

        {{-- Ngày công chiếu --}}
        <label>Ngày công chiếu</label>
        <input type="date" name="release_date" value="{{ $movie->release_date }}" class="form-control mb-3">

        {{-- Poster hiện tại --}}
        <label>Poster hiện tại</label><br>
        @if($movie->poster)
            <img src="/{{ $movie->poster }}" width="150" class="mb-3">
        @else
            <p class="text-muted">Chưa có poster</p>
        @endif

        {{-- Upload poster mới --}}
        <label>Poster mới (nếu có)</label>
        <input type="file" name="poster" class="form-control mb-3">

        <button class="btn btn-primary">Cập nhật</button>
    </form>

</div>

{{-- JS --}}
<script>
window.selectedGenres = @json($selectedGenres);
</script>


<script src="{{ asset('js/genre.js') }}"></script>

@endsection
