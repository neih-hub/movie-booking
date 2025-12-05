@extends('layouts.main')

@section('content')
<div class="container py-4">

    {{-- =================== SEARCH BOX =================== --}}
    <div class="search-box mb-5">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="fw-bold">1. Chọn phim</label>
                <select id="movie" class="form-select">
                    <option value="">-- Chọn phim --</option>
                    @foreach($movies as $m)
                        <option value="{{ $m->id }}">{{ $m->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="fw-bold">2. Chọn rạp</label>
                <select id="cinema" class="form-select">
                    <option value="">-- Chọn rạp --</option>
                    @foreach($cinemas as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="fw-bold">3. Ngày chiếu</label>
                <select id="date_start" class="form-select">
                    <option value="">-- Chọn ngày --</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="fw-bold">4. Suất chiếu</label>
                <select id="showtime" class="form-select">
                    <option value="">-- Chọn suất --</option>
                </select>
            </div>
        </div>

        <div class="text-end mt-4">
            <button id="btnBuy">Mua Vé Nhanh</button>
        </div>
    </div>

    {{-- =================== MOVIE LIST =================== --}}
    <h3 class="fw-bold mb-3">Phim đang chiếu</h3>

    <div class="movie-grid">
        @foreach($movies as $m)
        <div>
            <div class="movie-card">
                <img src="{{ asset($m->poster) }}" alt="{{ $m->title }}">

                <div class="movie-overlay">

                    {{-- Mua vé --}}
                    <a href="{{ route('movie.show', $m->id) }}" class="btn-buy">
                        <i class="bi bi-ticket-perforated"></i>
                        Mua vé
                    </a>

                    {{-- Trailer --}}
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

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endsection
