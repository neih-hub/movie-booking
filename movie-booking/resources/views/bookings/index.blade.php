<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt Vé Xem Phim</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking-selection.css') }}">
</head>

<body>
    <!-- Header -->
    @include('layouts.header')

    <div class="booking-selection-container">
        <div class="selection-header">
            <h1>🎬 Đặt Vé Xem Phim</h1>
            <p>Chọn phim và suất chiếu phù hợp với bạn</p>
        </div>

        <div class="selection-form">
            <div class="row g-4">
                <!-- bước 1: Chọn phim -->
                <div class="col-md-6">
                    <label class="form-label">
                        <span class="step-number">1</span>
                        Chọn phim
                    </label>
                    <select id="movie" class="form-select">
                        <option value="">-- Chọn phim --</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- bước 2: Chọn rạp -->
                <div class="col-md-6">
                    <label class="form-label">
                        <span class="step-number">2</span>
                        Chọn rạp
                    </label>
                    <select id="cinema" class="form-select">
                        <option value="">-- Chọn rạp --</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- bước 3: Chọn phòng -->
                <div class="col-md-6">
                    <label class="form-label">
                        <span class="step-number">3</span>
                        Chọn phòng
                    </label>
                    <select id="room" class="form-select">
                        <option value="">-- Chọn phòng --</option>
                    </select>
                </div>

                <!-- bước 4: Chọn ngày chiếu -->
                <div class="col-md-6">
                    <label class="form-label">
                        <span class="step-number">4</span>
                        Ngày chiếu
                    </label>
                    <select id="date_start" class="form-select">
                        <option value="">-- Chọn ngày --</option>
                    </select>
                </div>

                <!-- bước 5: Chọn suất chiếu -->
                <div class="col-12">
                    <label class="form-label">
                        <span class="step-number">5</span>
                        Suất chiếu
                    </label>
                    <div id="showtimeGrid" class="showtime-grid">
                        <div class="empty-state">
                            <i class="bi bi-film" style="font-size: 3rem;"></i>
                            <p class="mt-2">Vui lòng chọn phim, rạp, phòng và ngày để xem suất chiếu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiếp tục đặt vé -->
            <button type="button" class="btn btn-primary btn-continue" id="btnContinue" disabled>
                <i class="bi bi-arrow-right-circle"></i> Tiếp tục đặt vé
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/booking-selection.js') }}"></script>
</body>

</html>