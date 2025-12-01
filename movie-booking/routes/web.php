<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Controllers\MovieAdminController;


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
// ADMIN routes - protected by admin middleware
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\CinemaAdminController;
use App\Http\Controllers\ShowtimeAdminController;
use App\Http\Controllers\BookingAdminController;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserAdminController::class, 'list'])->name('admin.users.list');
        Route::get('/edit/{id}', [UserAdminController::class, 'edit'])->name('admin.users.edit');
        Route::post('/update/{id}', [UserAdminController::class, 'update'])->name('admin.users.update');
        Route::post('/toggle-status/{id}', [UserAdminController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::post('/delete/{id}', [UserAdminController::class, 'destroy'])->name('admin.users.delete');
    });

    // Movie Management
    Route::prefix('movies')->group(function () {
        Route::get('/', [MovieAdminController::class, 'list'])->name('admin.movies.list');
        Route::get('/create', [MovieAdminController::class, 'create'])->name('admin.movies.create');
        Route::post('/store', [MovieAdminController::class, 'store'])->name('admin.movies.store');
        Route::get('/edit/{id}', [MovieAdminController::class, 'edit'])->name('admin.movies.edit');
        Route::post('/update/{id}', [MovieAdminController::class, 'update'])->name('admin.movies.update');
        Route::post('/delete/{id}', [MovieAdminController::class, 'destroy'])->name('admin.movies.delete');
    });

    // Cinema Management
    Route::prefix('cinemas')->group(function () {
        Route::get('/', [CinemaAdminController::class, 'list'])->name('admin.cinemas.list');
        Route::get('/create', [CinemaAdminController::class, 'create'])->name('admin.cinemas.create');
        Route::post('/store', [CinemaAdminController::class, 'store'])->name('admin.cinemas.store');
        Route::get('/edit/{id}', [CinemaAdminController::class, 'edit'])->name('admin.cinemas.edit');
        Route::post('/update/{id}', [CinemaAdminController::class, 'update'])->name('admin.cinemas.update');
        Route::post('/delete/{id}', [CinemaAdminController::class, 'destroy'])->name('admin.cinemas.delete');
    });

    // Showtime Management
    Route::prefix('showtimes')->group(function () {
        Route::get('/', [ShowtimeAdminController::class, 'list'])->name('admin.showtimes.list');
        Route::get('/create', [ShowtimeAdminController::class, 'create'])->name('admin.showtimes.create');
        Route::post('/store', [ShowtimeAdminController::class, 'store'])->name('admin.showtimes.store');
        Route::get('/edit/{id}', [ShowtimeAdminController::class, 'edit'])->name('admin.showtimes.edit');
        Route::post('/update/{id}', [ShowtimeAdminController::class, 'update'])->name('admin.showtimes.update');
        Route::post('/delete/{id}', [ShowtimeAdminController::class, 'destroy'])->name('admin.showtimes.delete');
    });

    // Booking Management
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingAdminController::class, 'list'])->name('admin.bookings.list');
        Route::get('/show/{id}', [BookingAdminController::class, 'show'])->name('admin.bookings.show');
        Route::post('/cancel/{id}', [BookingAdminController::class, 'cancel'])->name('admin.bookings.cancel');
        Route::post('/delete/{id}', [BookingAdminController::class, 'destroy'])->name('admin.bookings.delete');
    });
});