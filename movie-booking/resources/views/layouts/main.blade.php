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

  {{-- CSS Header / Footer --}}
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="/css/footer.css"> 


  {{-- CSS được push từ view --}}
  @stack('styles')
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

  {{-- Header script --}}
  <script src="{{ asset('js/header.js') }}"></script>

  {{-- Scripts được push từ view --}}
  @stack('scripts')

</body>
</html>
