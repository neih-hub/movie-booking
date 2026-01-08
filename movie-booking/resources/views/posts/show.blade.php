@extends('layouts.main')

@section('title', $post->title)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/post-show.css') }}">

    <div class="container py-5">
        <div class="row">
            <!-- main content -->
            <div class="col-lg-8">
                <article class="post-detail">
                    <!-- post header -->
                    <header class="mb-4">
                        <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>

                        <div class="post-meta text-muted mb-3">
                            <span class="me-3">
                                <i class="bi bi-calendar"></i>
                                {{ $post->published_at->format('d/m/Y') }}
                            </span>
                            <span class="me-3">
                                <i class="bi bi-eye"></i>
                                {{ $post->views }} lượt xem
                            </span>
                            <span class="badge bg-info">
                                {{ ucfirst($post->category) }}
                            </span>
                        </div>

                        @if($post->author)
                            <div class="author-info mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-person"></i>
                                    Tác giả: <strong>{{ $post->author->name }}</strong>
                                </small>
                            </div>
                        @endif
                    </header>

                    @if($post->thumbnail)
                        <div class="post-thumbnail mb-4">
                            <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                        </div>
                    @endif

                    @if($post->excerpt)
                        <div class="post-excerpt alert alert-light mb-4">
                            <p class="lead mb-0">{{ $post->excerpt }}</p>
                        </div>
                    @endif

                    <div class="post-content">
                        {!! $post->content !!}
                    </div>
                    <div class="share-buttons mt-5 pt-4 border-top">
                        <h5 class="mb-3">Chia sẻ bài viết</h5>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank" class="btn btn-primary">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                target="_blank" class="btn btn-info text-white">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                        </div>
                    </div>
                </article>

                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                    <section class="related-posts mt-5 pt-5 border-top">
                        <h3 class="mb-4">Bài viết liên quan</h3>
                        <div class="row g-4">
                            @foreach($relatedPosts as $related)
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        @if($related->thumbnail)
                                            <img src="{{ asset($related->thumbnail) }}" class="card-img-top"
                                                alt="{{ $related->title }}">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('post.show', $related->id) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $related->title }}
                                                </a>
                                            </h5>
                                            @if($related->excerpt)
                                                <p class="card-text text-muted small">{{ Str::limit($related->excerpt, 100) }}</p>
                                            @endif
                                            <div class="small text-muted">
                                                <i class="bi bi-calendar"></i> {{ $related->published_at->format('d/m/Y') }}
                                                <span class="ms-2">
                                                    <i class="bi bi-eye"></i> {{ $related->views }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <!-- sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Danh mục</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-film"></i> Review phim
                                </a>
                                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-newspaper"></i> Tin tức
                                </a>
                                <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-book"></i> Bài viết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection