@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/theater.css') }}">
@endsection

@section('content')
<div class="container py-5">
    
    
    <div class="cinema-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="cinema-title">{{ $cinema->name }}</h1>
                @if($cinema->address)
                <p class="cinema-address">
                    <i class="bi bi-geo-alt-fill"></i> {{ $cinema->address }}
                </p>
                @endif
            </div>
            <div class="col-md-4 text-end">
                <div class="cinema-selector">
                    <select class="form-select" id="cinemaSelector" onchange="window.location.href='/theaters/' + this.value">
                        @foreach($cinemas as $c)
                            <option value="{{ $c->id }}" {{ $c->id == $cinema->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    
    <div class="showtimes-section">
        <h3 class="section-title mb-4">
            <i class="bi bi-film"></i> Lịch chiếu
        </h3>

        @if($groupedShowtimes->count() > 0)
            @foreach($groupedShowtimes as $movieId => $dateGroups)
                @php
                    $firstShowtime = $dateGroups->first()->first();
                    $movie = $firstShowtime->movie;
                @endphp

                <div class="movie-showtime-card mb-4">
                    <div class="row">
                        
                        <div class="col-md-2">
                            <img src="{{ asset($movie->poster) }}" 
                                 alt="{{ $movie->title }}" 
                                 class="img-fluid rounded shadow-sm">
                        </div>

                        
                        <div class="col-md-10">
                            <h4 class="movie-title mb-3">{{ $movie->title }}</h4>
                            
                            <div class="movie-meta mb-3">
                                @if(is_array($movie->genre))
                                    @foreach($movie->genre as $genre)
                                        <span class="badge bg-primary me-1">{{ $genre }}</span>
                                    @endforeach
                                @endif
                                <span class="text-muted ms-2">
                                    <i class="bi bi-clock"></i> {{ $movie->duration }} phút
                                </span>
                            </div>

                            
                            @foreach($dateGroups as $date => $showtimes)
                                <div class="date-group mb-3">
                                    <div class="date-header">
                                        <i class="bi bi-calendar-event"></i>
                                        <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong>
                                        <span class="text-muted ms-2">
                                            ({{ \Carbon\Carbon::parse($date)->locale('vi')->dayName }})
                                        </span>
                                    </div>

                                    <div class="showtime-buttons mt-2">
                                        @foreach($showtimes as $showtime)
                                            <a href="{{ route('booking.create', $showtime->id) }}" 
                                               class="btn btn-outline-primary showtime-btn">
                                                <div class="showtime-time">
                                                    {{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}
                                                </div>
                                                <div class="showtime-room">{{ $showtime->room->name }}</div>
                                                <div class="showtime-price">{{ number_format($showtime->price) }}đ</div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!$loop->last)
                    <hr class="my-4">
                @endif
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Hiện tại chưa có lịch chiếu nào tại rạp này.
            </div>
        @endif
    </div>

</div>
@endsection
