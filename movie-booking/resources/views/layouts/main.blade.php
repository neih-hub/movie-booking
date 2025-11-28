<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Movie Booking' }}</title>

  {{-- Bootstrap CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  {{-- CSS Header --}}
  <link rel="stylesheet" href="/css/header.css">

  {{-- CSS chung cho website (nếu có) --}}
  <link rel="stylesheet" href="/css/main.css">
</head>

<body>

  {{-- HEADER --}}
  @include('layouts.header')

  {{-- CONTENT --}}
  <main>
    @yield('content')
  </main>

  {{-- FOOTER --}}
  @include('layouts.footer')

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/header.js"></script>

</body>

</html>