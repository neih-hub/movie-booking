
@if(isset($latestPosts) && $latestPosts->count() > 0)
<div class="blog-section mt-5">

    
    <div class="blog-section-header">
        <h3 class="blog-section-title">GÓC ĐIỆN ẢNH</h3>
        
        <div class="blog-tabs">
            <button class="blog-tab-btn active" data-blog-tab="review">Bình luận phim</button>
            <button class="blog-tab-btn" data-blog-tab="news">Blog điện ảnh</button>
        </div>
    </div>

    
    <div class="blog-tab-content active" id="blog-review">
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
                        <span><i class="bi bi-calendar"></i> {{ $firstReview->published_at->format('d/m/Y') }}</span>
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

    
    <div class="text-center mt-3">
        <a href="{{ route('posts.index') }}" class="btn-view-more">
            Xem thêm <i class="bi bi-arrow-right"></i>
        </a>
    </div>

</div>
@endif
