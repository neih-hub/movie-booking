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
// đăng kí
use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// dang nhap
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// dat ve
Route::get('/booking', [BookingController::class, 'index'])
    ->middleware(['auth', 'profile.completed']);
// cap nhat thong tin ca nhan
use App\Http\Controllers\ProfileController;
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
}); 
// dang xuat
use Illuminate\Support\Facades\Auth;
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Bạn đã đăng xuất!');
})->name('logout');