<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Models\Movie;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
| Trang chủ chỉ hiển thị giao diện home.index
| (Nếu sau này bạn muốn trang chủ load phim thì sẽ đổi sang MovieController)
*/
Route::get('/', function () {
    return view('home.index', ['title' => 'Trang chủ']);
});


/*
|--------------------------------------------------------------------------
| MOVIE (PHIM)
|--------------------------------------------------------------------------
| Xem danh sách phim và chi tiết phim
*/
Route::get('/movies', [MovieController::class, 'index']);    // Danh sách phim
Route::get('/movie/{id}', [MovieController::class, 'show']); // Chi tiết phim


/*
|--------------------------------------------------------------------------
| SHOWTIME (LỊCH CHIẾU)
|--------------------------------------------------------------------------
*/
Route::get('/showtime/{id}', [ShowtimeController::class, 'show']);


/*
|--------------------------------------------------------------------------
| BOOKING (ĐẶT VÉ)
|--------------------------------------------------------------------------
*/
Route::post('/booking', [BookingController::class, 'store']);


/*
|--------------------------------------------------------------------------
| SEARCH AUTOCOMPLETE
|--------------------------------------------------------------------------
*/
Route::get('/search-movie', function (Request $request) {
    $query = $request->query;

    $movies = Movie::where('title', 'like', "%$query%")
        ->take(5)
        ->get(['id', 'title']);

    return response()->json($movies);
});