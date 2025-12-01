@extends('layouts.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/genre.css') }}">
@endpush

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Thêm phim</h3>

    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control mb-3" required>

        <label>Mô tả</label>
        <textarea name="description" class="form-control mb-3"></textarea>

        <label>Thời lượng (phút)</label>
        <input type="number" name="duration" class="form-control mb-3">

        {{-- THỂ LOẠI --}}
        <label>Thể loại (chọn tối đa 4)</label>

        @php
            $genres = [
                'Hành động', 'Phiêu lưu', 'Khoa học viễn tưởng', 'Kinh dị',
                'Tâm lý', 'Tình cảm', 'Hài', 'Hoạt hình', 'Gia đình',
                'Chiến tranh', 'Hình sự', 'Thể thao', 'Nhạc kịch',
                'Viễn Tây', 'Giả tưởng', 'Bí ẩn', 'Tài liệu',
                'Lịch sử', 'Phiêu lưu - Hành động', 'Anime'
            ];
        @endphp

        <div id="genre-wrapper" class="d-flex flex-wrap gap-2 mb-3">
            @foreach($genres as $g)
                <button type="button" class="genre-btn" data-value="{{ $g }}">
                    {{ $g }}
                </button>
            @endforeach
        </div>

        <input type="hidden" name="genre" id="genreInput">

        <label>Ngày công chiếu</label>
        <input type="date" name="release_date" class="form-control mb-3">

        <label>Poster</label>
        <input type="file" name="poster" class="form-control mb-3">

        <button class="btn btn-primary">Lưu</button>
    </form>

</div>

<script>
    window.selectedGenres = []; // mặc định không có genre nào được chọn
</script>

<script src="{{ asset('js/genre.js') }}"></script>

@endsection
