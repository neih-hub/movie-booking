@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Movie Poster -->
        <div class="col-md-4">
            <img src="{{ asset($movie->poster) }}" 
     alt="{{ $movie->title }}" 
     class="img-fluid rounded shadow-lg">


        </div>

        <!-- Movie Details -->
        <div class="col-md-8">
            <h1 class="mb-3" style="font-weight: 700; color: #1e293b;">{{ $movie->title }}</h1>
            
            <div class="mb-4">
                @if(is_array($movie->genre))
                    @foreach($movie->genre as $genre)
                        <span class="badge bg-primary me-2">{{ $genre }}</span>
                    @endforeach
                @endif
            </div>

            <div class="mb-3">
                <i class="bi bi-clock text-primary"></i>
                <strong>Thời lượng:</strong> {{ $movie->duration }} phút
            </div>

            <div class="mb-3">
                <i class="bi bi-calendar text-primary"></i>
                <strong>Ngày khởi chiếu:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Mô tả</h5>
                <p class="text-muted">{{ $movie->description }}</p>
            </div>

            <!-- Showtimes Section -->
            <div class="mt-5">
                <h4 class="mb-4" style="font-weight: 700;">Lịch chiếu</h4>
                
                @if($movie->showtimes && $movie->showtimes->count() > 0)
                    @php
                        $showtimesByDate = $movie->showtimes->groupBy(function($showtime) {
                            return \Carbon\Carbon::parse($showtime->date_start)->format('Y-m-d');
                        });
                    @endphp

                    @foreach($showtimesByDate as $date => $showtimes)
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    ({{ \Carbon\Carbon::parse($date)->locale('vi')->dayName }})
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($showtimes as $showtime)
                                        <div class="col-md-6">
                                            <div class="border rounded p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $showtime->room->cinema->name }}</h6>
                                                        <p class="mb-1 text-muted small">
                                                            <i class="bi bi-door-open"></i> {{ $showtime->room->name }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <i class="bi bi-clock"></i>
                                                            <strong>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</strong>
                                                        </p>
                                                        <p class="mb-0 text-primary fw-bold">
                                                            {{ number_format($showtime->price) }} đ
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('booking.create', $showtime->id) }}" 
                                                           class="btn btn-primary" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border: #ff9a9e">
                                                            <i class="bi bi-ticket-perforated"></i> Đặt vé
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Hiện tại chưa có lịch chiếu cho phim này.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    }

    .border {
        transition: all 0.3s;
    }

    .border:hover {
        box-shadow: 0 4px 12px rgba(255, 154, 158, 0.2);
        border-color: #ff9a9e !important;
    }
</style>
@endsection
