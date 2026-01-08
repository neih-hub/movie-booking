<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Movie Booking' }}</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nabla&family=Poetsen+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/home.css">
  <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
  @stack('styles')
  @yield('head')
</head>

<body>

  @include('layouts.header')

  <main>
    @yield('content')
  </main>

  @include('layouts.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{ asset('js/header.js') }}"></script>

  @stack('scripts')

  @yield('scripts')

</body>

</html>