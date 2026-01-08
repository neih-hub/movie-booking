@extends('layouts.main')

@section('content')
    <div class="container py-4">

        <!-- ddặt vé nhanh -->
        <div class="quick-booking-section mb-5">
            <h3 class="section-title-with-bar">ĐẶT VÉ NHANH</h3>

            <div class="quick-booking-card">
                <div class="row g-3">

                    <!-- 1. Chọn phim -->
                    <div class="col-md-3">
                        <label class="quick-booking-label">1. Chọn phim</label>
                        <select id="movie" class="form-select">
                            <option value="">-- Chọn phim --</option>
                            @foreach($nowShowing as $m)
                                <option value="{{ $m->id }}">{{ $m->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Chọn rạp -->
                    <div class="col-md-3">
                        <label class="quick-booking-label">2. Chọn rạp</label>
                        <select id="cinema" class="form-select">
                            <option value="">-- Chọn rạp --</option>
                            @foreach($cinemas as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Chọn phòng -->
                    <div class="col-md-3">
                        <label class="quick-booking-label">3. Chọn phòng</label>
                        <select id="room" class="form-select">
                            <option value="">-- Chọn phòng --</option>
                        </select>
                    </div>

                    <!-- 4. Ngày chiếu -->
                    <div class="col-md-3">
                        <label class="quick-booking-label">4. Ngày chiếu</label>
                        <select id="date_start" class="form-select">
                            <option value="">-- Chọn ngày --</option>
                        </select>
                    </div>

                    {{-- 5. Suất chiếu --}}
                    <div class="col-md-3 mt-3">
                        <label class="quick-booking-label">5. Suất chiếu</label>
                        <select id="showtime" class="form-select">
                            <option value="">-- Chọn suất --</option>
                        </select>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <button id="btnBuy" class="btn-quick-book">
                        <i class="bi bi-ticket-perforated"></i> Mua Vé Nhanh
                    </button>
                </div>
            </div>
        </div>

        <div class="movie-tabs-container">

            <h3 class="section-title-with-bar">DANH MỤC PHIM</h3>

            <div class="movie-tabs">
                <button class="tab-btn active" data-tab="now-showing">Đang chiếu</button>
                <button class="tab-btn" data-tab="coming-soon">Sắp chiếu</button>
                <button class="tab-btn" data-tab="imax">Phim IMAX</button>
                <button class="tab-btn" data-tab="all">
                    <i class="bi bi-globe"></i> Toàn quốc
                </button>
            </div>

            <div class="tab-content active" id="now-showing">
                <div class="movie-grid-5col">
                    @foreach($nowShowing as $index => $m)
                        <div class="movie-item" data-index="{{ $index }}">
                            <div class="movie-card">
                                <img src="{{ asset($m->poster) }}" alt="{{ $m->title }}">

                                <div class="movie-overlay">
                                    <a href="{{ route('movie.show', $m->id) }}" class="btn-buy">
                                        <i class="bi bi-ticket-perforated"></i>
                                        Mua vé
                                    </a>
                                    <button class="btn-trailer">
                                        <i class="bi bi-play-circle"></i>
                                        Trailer
                                    </button>
                                </div>
                            </div>
                            <h5 class="movie-title mt-2">{{ $m->title }}</h5>
                        </div>
                    @endforeach
                </div>

                @if(count($nowShowing) > 10)
                    <div class="text-center mt-4">
                        <button class="btn-load-more" data-tab="now-showing">
                            Xem thêm <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                @endif
            </div>

            <div class="tab-content" id="coming-soon">
                <div class="movie-grid-5col">
                    @forelse($comingSoon as $index => $m)
                        <div class="movie-item" data-index="{{ $index }}">
                            <div class="movie-card">
                                <img src="{{ asset($m->poster) }}" alt="{{ $m->title }}">

                                <div class="movie-overlay">
                                    <a href="{{ route('movie.show', $m->id) }}" class="btn-buy">
                                        <i class="bi bi-ticket-perforated"></i>
                                        Mua vé
                                    </a>
                                    <button class="btn-trailer">
                                        <i class="bi bi-play-circle"></i>
                                        Trailer
                                    </button>
                                </div>
                            </div>

                            <h5 class="movie-title mt-2">{{ $m->title }}</h5>
                            <p class="text-muted small">
                                Khởi chiếu: {{ date('d/m/Y', strtotime($m->release_date)) }}
                            </p>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-film" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Chưa có phim sắp chiếu</p>
                        </div>
                    @endforelse
                </div>

                @if(count($comingSoon) > 10)
                    <div class="text-center mt-4">
                        <button class="btn-load-more" data-tab="coming-soon">
                            Xem thêm <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                @endif
            </div>

            <div class="tab-content" id="imax">
                <div class="text-center py-5">
                    <i class="bi bi-film" style="font-size: 4rem; color: #f97316;"></i>
                    <h4 class="mt-3">Phim IMAX</h4>
                    <p class="text-muted">Tính năng đang được phát triển...</p>
                </div>
            </div>

            <div class="tab-content" id="all">
                <div class="text-center py-5">
                    <i class="bi bi-globe" style="font-size: 4rem; color: #2563eb;"></i>
                    <h4 class="mt-3">Toàn quốc</h4>
                    <p class="text-muted">Tính năng đang được phát triển...</p>
                </div>
            </div>
        </div>

        @if(isset($latestPosts) && $latestPosts->count() > 0)
            <div class="blog-section mt-5">

                <!-- Header -->
                <div class="blog-section-header">
                    <h3 class="blog-section-title">GÓC ĐIỆN ẢNH</h3>

                    <div class="blog-tabs">
                        <button class="blog-tab-btn active" data-blog-tab="all">Tất cả</button>
                        <button class="blog-tab-btn" data-blog-tab="review">Review phim</button>
                        <button class="blog-tab-btn" data-blog-tab="news">Tin tức</button>
                        <button class="blog-tab-btn" data-blog-tab="article">Bài viết</button>
                    </div>
                </div>

                <div class="blog-tab-content active" id="blog-all">
                    <div class="blog-grid">
                        @php
                            $firstPost = $latestPosts->first();
                        @endphp

                        @if($firstPost)
                            <div class="blog-featured">
                                <a href="{{ route('post.show', $firstPost->id) }}">
                                    <img src="{{ asset($firstPost->thumbnail) }}" class="featured-img">
                                </a>

                                <div class="blog-content">
                                    <h4 class="blog-title">
                                        <a href="{{ route('post.show', $firstPost->id) }}">
                                            {{ $firstPost->title }}
                                        </a>
                                    </h4>

                                    <div class="blog-meta">
                                        <span class="badge bg-info">{{ ucfirst($firstPost->category) }}</span>
                                        <span><i class="bi bi-eye"></i> {{ $firstPost->views }}</span>
                                        <span><i class="bi bi-calendar"></i> {{ $firstPost->published_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="blog-list">
                            @foreach($latestPosts->skip(1)->take(3) as $post)
                                <div class="blog-item">
                                    <a href="{{ route('post.show', $post->id) }}">
                                        <img src="{{ asset($post->thumbnail) }}" class="blog-thumb">
                                    </a>

                                    <div class="blog-info">
                                        <h5 class="blog-item-title">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h5>

                                        <div class="blog-meta small">
                                            <span class="badge bg-info">{{ ucfirst($post->category) }}</span>
                                            <i class="bi bi-eye"></i> {{ $post->views }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="blog-tab-content" id="blog-review">
                    <div class="blog-grid">
                        @php
                            $reviewPosts = $latestPosts->where('category', 'review');
                            $firstReview = $reviewPosts->first();
                        @endphp

                        @if($firstReview)
                            <div class="blog-featured">
                                <a href="{{ route('post.show', $firstReview->id) }}">
                                    <img src="{{ asset($firstReview->thumbnail) }}" class="featured-img">
                                </a>

                                <div class="blog-content">
                                    <h4 class="blog-title">
                                        <a href="{{ route('post.show', $firstReview->id) }}">
                                            {{ $firstReview->title }}
                                        </a>
                                    </h4>

                                    <div class="blog-meta">
                                        <span><i class="bi bi-eye"></i> {{ $firstReview->views }}</span>
                                        <span><i class="bi bi-calendar"></i>
                                            {{ $firstReview->published_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-film" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Chưa có bài bình luận phim</p>
                            </div>
                        @endif

                        <div class="blog-list">
                            @foreach($reviewPosts->skip(1)->take(3) as $post)
                                <div class="blog-item">
                                    <a href="{{ route('post.show', $post->id) }}">
                                        <img src="{{ asset($post->thumbnail) }}" class="blog-thumb">
                                    </a>

                                    <div class="blog-info">
                                        <h5 class="blog-item-title">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h5>

                                        <div class="blog-meta small">
                                            <i class="bi bi-eye"></i> {{ $post->views }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="blog-tab-content" id="blog-news">
                    <div class="blog-grid">
                        @php
                            $newsPosts = $latestPosts->where('category', 'news');
                            $firstNews = $newsPosts->first();
                        @endphp

                        @if($firstNews)
                            <div class="blog-featured">
                                <a href="{{ route('post.show', $firstNews->id) }}">
                                    <img src="{{ asset($firstNews->thumbnail) }}" class="featured-img">
                                </a>

                                <div class="blog-content">
                                    <h4 class="blog-title">
                                        <a href="{{ route('post.show', $firstNews->id) }}">
                                            {{ $firstNews->title }}
                                        </a>
                                    </h4>

                                    <div class="blog-meta">
                                        <span><i class="bi bi-eye"></i> {{ $firstNews->views }}</span>
                                        <span><i class="bi bi-calendar"></i> {{ $firstNews->published_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-newspaper" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Chưa có bài viết</p>
                            </div>
                        @endif

                        <div class="blog-list">
                            @foreach($newsPosts->skip(1)->take(3) as $post)
                                <div class="blog-item">
                                    <a href="{{ route('post.show', $post->id) }}">
                                        <img src="{{ asset($post->thumbnail) }}" class="blog-thumb">
                                    </a>

                                    <div class="blog-info">
                                        <h5 class="blog-item-title">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h5>

                                        <div class="blog-meta small">
                                            <i class="bi bi-eye"></i> {{ $post->views }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="blog-tab-content" id="blog-article">
                    <div class="blog-grid">
                        @php
                            $articlePosts = $latestPosts->where('category', 'article');
                            $firstArticle = $articlePosts->first();
                        @endphp

                        @if($firstArticle)
                            <div class="blog-featured">
                                <a href="{{ route('post.show', $firstArticle->id) }}">
                                    <img src="{{ asset($firstArticle->thumbnail) }}" class="featured-img">
                                </a>

                                <div class="blog-content">
                                    <h4 class="blog-title">
                                        <a href="{{ route('post.show', $firstArticle->id) }}">
                                            {{ $firstArticle->title }}
                                        </a>
                                    </h4>

                                    <div class="blog-meta">
                                        <span><i class="bi bi-eye"></i> {{ $firstArticle->views }}</span>
                                        <span><i class="bi bi-calendar"></i>
                                            {{ $firstArticle->published_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-book" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Chưa có bài viết</p>
                            </div>
                        @endif

                        <div class="blog-list">
                            @foreach($articlePosts->skip(1)->take(3) as $post)
                                <div class="blog-item">
                                    <a href="{{ route('post.show', $post->id) }}">
                                        <img src="{{ asset($post->thumbnail) }}" class="blog-thumb">
                                    </a>

                                    <div class="blog-info">
                                        <h5 class="blog-item-title">
                                            <a href="{{ route('post.show', $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h5>

                                        <div class="blog-meta small">
                                            <i class="bi bi-eye"></i> {{ $post->views }}
                                            &nbsp;&nbsp;
                                            <i class="bi bi-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- xem thêm -->
                <div class="text-center mt-3">
                    <a href="{{ route('posts.index') }}" class="btn-view-more">
                        Xem thêm <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script>
        window.baseUrl = "{{ url('/') }}";
    </script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/home-tabs.js') }}"></script>
@endsection