@extends('layouts.main')

@section('title', 'Góc Điện Ảnh')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại trang chủ
        </a>
    </div>

    <!-- Page Header -->
    <div class="page-header mb-5">
        <h1 class="display-4 fw-bold mb-3">Góc Điện Ảnh</h1>
        <p class="lead text-muted">Khám phá thế giới điện ảnh qua những bài viết, review và tin tức mới nhất</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('category') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                    <i class="bi bi-grid"></i> Tất cả
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('category') == 'review' ? 'active' : '' }}" href="{{ route('posts.index', ['category' => 'review']) }}">
                    <i class="bi bi-film"></i> Review phim
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('category') == 'news' ? 'active' : '' }}" href="{{ route('posts.index', ['category' => 'news']) }}">
                    <i class="bi bi-newspaper"></i> Tin tức
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('category') == 'article' ? 'active' : '' }}" href="{{ route('posts.index', ['category' => 'article']) }}">
                    <i class="bi bi-book"></i> Bài viết
                </a>
            </li>
        </ul>
    </div>

    <!-- Posts Grid -->
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 post-card">
                @if($post->thumbnail)
                <a href="{{ route('post.show', $post->id) }}">
                    <img src="{{ asset($post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}">
                </a>
                @else
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-info">{{ ucfirst($post->category) }}</span>
                    </div>
                    
                    <h5 class="card-title">
                        <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark">
                            {{ $post->title }}
                        </a>
                    </h5>
                    
                    @if($post->excerpt)
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($post->excerpt, 120) }}
                    </p>
                    @endif
                    
                    <div class="post-meta small text-muted mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-calendar"></i>
                                {{ $post->published_at->format('d/m/Y') }}
                            </span>
                            <span>
                                <i class="bi bi-eye"></i>
                                {{ $post->views }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top-0">
                    <a href="{{ route('post.show', $post->id) }}" class="btn btn-outline-primary btn-sm w-100">
                        Đọc tiếp <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3 text-muted">Chưa có bài viết nào</h4>
                <p class="text-muted">Hãy quay lại sau để đọc những bài viết mới nhất!</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-5">
        {{ $posts->links() }}
    </div>
    @endif
</div>

<style>
.post-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.post-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.post-card .card-img-top {
    height: 200px;
    object-fit: cover;
    transition: opacity 0.3s;
}

.post-card:hover .card-img-top {
    opacity: 0.9;
}

.post-card .card-title a {
    transition: color 0.2s;
}

.post-card .card-title a:hover {
    color: #0d6efd !important;
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 20px;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
}

.page-header {
    border-bottom: 3px solid #0d6efd;
    padding-bottom: 1rem;
}
</style>
@endsection
