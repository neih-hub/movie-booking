@extends('layouts.main')

@section('content')
<div class="container py-4">

    {{-- =================== SEARCH BOX =================== --}}
    <div class="search-box mb-5">
        <div class="row g-3">

            {{-- 1. Chọn phim --}}
            <div class="col-md-3">
                <label class="fw-bold">1. Chọn phim</label>
                <select id="movie" class="form-select">
                    <option value="">-- Chọn phim --</option>
                    @foreach($nowShowing as $m)
                        <option value="{{ $m->id }}">{{ $m->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Chọn rạp --}}
            <div class="col-md-3">
                <label class="fw-bold">2. Chọn rạp</label>
                <select id="cinema" class="form-select">
                    <option value="">-- Chọn rạp --</option>
                    @foreach($cinemas as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. Chọn phòng --}}
            <div class="col-md-3">
                <label class="fw-bold">3. Chọn phòng</label>
                <select id="room" class="form-select">
                    <option value="">-- Chọn phòng --</option>
                </select>
            </div>

            {{-- 4. Ngày chiếu --}}
            <div class="col-md-3">
                <label class="fw-bold">4. Ngày chiếu</label>
                <select id="date_start" class="form-select">
                    <option value="">-- Chọn ngày --</option>
                </select>
            </div>

            {{-- 5. Suất chiếu --}}
            <div class="col-md-3 mt-3">
                <label class="fw-bold">5. Suất chiếu</label>
                <select id="showtime" class="form-select">
                    <option value="">-- Chọn suất --</option>
                </select>
            </div>

        </div>

        <div class="text-end mt-4">
            <button id="btnBuy" class="btn btn-success px-4">
                <i class="bi bi-ticket-perforated"></i> Mua Vé Nhanh
            </button>
        </div>
    </div>

    {{-- =================== MOVIE TABS =================== --}}
    <div class="movie-tabs-container">

        {{-- Tab Navigation --}}
        <div class="movie-tabs">
            <button class="tab-btn active" data-tab="now-showing">Đang chiếu</button>
            <button class="tab-btn" data-tab="coming-soon">Sắp chiếu</button>
            <button class="tab-btn" data-tab="imax">Phim IMAX</button>
            <button class="tab-btn" data-tab="all">
                <i class="bi bi-globe"></i> Toàn quốc
            </button>
        </div>

        {{-- Tab Content: Đang chiếu --}}
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

        {{-- Tab Content: Sắp chiếu --}}
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

        {{-- Placeholder Tabs --}}
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

    {{-- =================== GÓC ĐIỆN ẢNH =================== --}}
    @if(isset($latestPosts) && $latestPosts->count() > 0)
    <div class="blog-section mt-5">

        {{-- Header --}}
        <div class="section-header mb-3 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold">GÓC ĐIỆN ẢNH</h3>

            <div class="section-tabs">
                <button class="blog-tab active">Bình luận phim</button>
                <button class="blog-tab">Blog điện ảnh</button>
            </div>
        </div>

        <div class="blog-grid">

            {{-- Featured post --}}
            @if($latestPosts->first())
            <div class="blog-featured">
                <a href="{{ route('post.show', $latestPosts[0]->slug) }}">
                    <img src="{{ asset($latestPosts[0]->thumbnail) }}" class="featured-img">
                </a>

                <div class="blog-content">
                    <h4 class="blog-title">
                        <a href="{{ route('post.show', $latestPosts[0]->slug) }}">
                            {{ $latestPosts[0]->title }}
                        </a>
                    </h4>

                    <div class="blog-meta">
                        <span><i class="bi bi-eye"></i> {{ $latestPosts[0]->views }}</span>
                        <span><i class="bi bi-calendar"></i> {{ $latestPosts[0]->published_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Smaller posts --}}
            <div class="blog-list">
                @foreach($latestPosts->skip(1)->take(3) as $post)
                <div class="blog-item">
                    <a href="{{ route('post.show', $post->slug) }}">
                        <img src="{{ asset($post->thumbnail) }}" class="blog-thumb">
                    </a>

                    <div class="blog-info">
                        <h5 class="blog-item-title">
                            <a href="{{ route('post.show', $post->slug) }}">
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

        {{-- View more --}}
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
<script src="{{ asset('js/home.js') }}"></script>
<script src="{{ asset('js/home-tabs.js') }}"></script>
@endsection
