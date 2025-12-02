<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\MovieAdminController;
use App\Http\Controllers\CinemaAdminController;
use App\Http\Controllers\RoomAdminController;
use App\Http\Controllers\SeatAdminController;
use App\Http\Controllers\ShowtimeAdminController;
use App\Http\Controllers\BookingAdminController;

use App\Models\User;


// ==========================
// TRANG CHỦ
// ==========================
Route::get('/', function () {
    return view('home.index', ['title' => 'Trang chủ']);
});


// ==========================
// MOVIES (CLIENT)
// ==========================
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movie/{slug}', [MovieController::class, 'show'])->name('movie.show');


// ==========================
// SHOWTIME (CLIENT)
// ==========================
Route::get('/showtime/{id}', [ShowtimeController::class, 'show']);


// ==========================
// BOOKING (CLIENT)
// ==========================
Route::post('/booking', [BookingController::class, 'store']);

Route::get('/booking', [BookingController::class, 'index'])
    ->middleware(['auth', 'profile.completed']);


// ==========================
// AJAX SEARCH MOVIE
// ==========================
Route::get('/search-movie', function (Request $req) {
    $query = $req->query;

    $movies = \App\Models\Movie::where('title', 'like', "%$query%")
        ->take(5)
        ->get(['title', 'slug']);

    return response()->json($movies);
});


// ==========================
// AUTH
// ==========================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Bạn đã đăng xuất!');
})->name('logout');


// ==========================
// PROFILE (USER)
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});


// ==========================
// TẠO ADMIN (DÙNG 1 LẦN)
// ==========================
Route::get('/create-admin', function () {
    $admin = User::create([
        'name' => 'Administrator',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('admin123'),
        'role' => 0,
        'status' => 1,
    ]);

    return "Tạo admin thành công: " . $admin->email;
});


// ==========================
// ADMIN PANEL
// ==========================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserAdminController::class, 'list'])->name('admin.users.list');
        Route::get('/edit/{id}', [UserAdminController::class, 'edit'])->name('admin.users.edit');
        Route::post('/update/{id}', [UserAdminController::class, 'update'])->name('admin.users.update');
        Route::post('/toggle/{id}', [UserAdminController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::post('/delete/{id}', [UserAdminController::class, 'destroy'])->name('admin.users.delete');
    });

    // Movies
    Route::prefix('movies')->group(function () {
        Route::get('/', [MovieAdminController::class, 'list'])->name('admin.movies.list');
        Route::get('/create', [MovieAdminController::class, 'create'])->name('admin.movies.create');
        Route::post('/store', [MovieAdminController::class, 'store'])->name('admin.movies.store');
        Route::get('/edit/{id}', [MovieAdminController::class, 'edit'])->name('admin.movies.edit');
        Route::post('/update/{id}', [MovieAdminController::class, 'update'])->name('admin.movies.update');
        Route::post('/delete/{id}', [MovieAdminController::class, 'destroy'])->name('admin.movies.delete');
    });

    // Cinemas
    Route::prefix('cinemas')->group(function () {
        Route::get('/', [CinemaAdminController::class, 'list'])->name('admin.cinemas.list');
        Route::get('/create', [CinemaAdminController::class, 'create'])->name('admin.cinemas.create');
        Route::post('/store', [CinemaAdminController::class, 'store'])->name('admin.cinemas.store');
        Route::get('/edit/{id}', [CinemaAdminController::class, 'edit'])->name('admin.cinemas.edit');
        Route::post('/update/{id}', [CinemaAdminController::class, 'update'])->name('admin.cinemas.update');
        Route::post('/delete/{id}', [CinemaAdminController::class, 'destroy'])->name('admin.cinemas.delete');
    });

    // ⭐ Route xem ghế phải nằm bên trong group admin
    Route::get('/rooms/{id}/seats', [CinemaAdminController::class, 'showSeats'])
        ->name('admin.rooms.show');

    // Room Management
    Route::prefix('rooms')->group(function () {
        Route::get('/manage', [RoomAdminController::class, 'manage'])->name('admin.rooms.manage');
        Route::get('/{id}/seats-honeycomb', [RoomAdminController::class, 'showSeatsHoneycomb'])->name('admin.rooms.seats.honeycomb');
        Route::get('/', [RoomAdminController::class, 'list'])->name('admin.rooms.list');
        Route::get('/create', [RoomAdminController::class, 'create'])->name('admin.rooms.create');
        Route::post('/store', [RoomAdminController::class, 'store'])->name('admin.rooms.store');
        Route::get('/edit/{id}', [RoomAdminController::class, 'edit'])->name('admin.rooms.edit');
        Route::post('/update/{id}', [RoomAdminController::class, 'update'])->name('admin.rooms.update');
        Route::post('/delete/{id}', [RoomAdminController::class, 'destroy'])->name('admin.rooms.delete');
    });

    // Showtimes
    Route::prefix('showtimes')->group(function () {
        Route::get('/', [ShowtimeAdminController::class, 'list'])->name('admin.showtimes.list');
        Route::get('/create', [ShowtimeAdminController::class, 'create'])->name('admin.showtimes.create');
        Route::post('/store', [ShowtimeAdminController::class, 'store'])->name('admin.showtimes.store');
        Route::get('/edit/{id}', [ShowtimeAdminController::class, 'edit'])->name('admin.showtimes.edit');
        Route::post('/update/{id}', [ShowtimeAdminController::class, 'update'])->name('admin.showtimes.update');
        Route::post('/delete/{id}', [ShowtimeAdminController::class, 'destroy'])->name('admin.showtimes.delete');
    });

    // Bookings
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingAdminController::class, 'list'])->name('admin.bookings.list');
        Route::get('/show/{id}', [BookingAdminController::class, 'show'])->name('admin.bookings.show');
        Route::post('/cancel/{id}', [BookingAdminController::class, 'cancel'])->name('admin.bookings.cancel');
        Route::post('/delete/{id}', [BookingAdminController::class, 'destroy'])->name('admin.bookings.delete');
    });

    // ==========================
    // SEAT MANAGEMENT (NEW)
    // ==========================
    Route::prefix('seats')->group(function () {
        Route::get('/', [SeatAdminController::class, 'list'])->name('admin.seats.list');
        Route::get('/create', [SeatAdminController::class, 'create'])->name('admin.seats.create');
        Route::post('/store', [SeatAdminController::class, 'store'])->name('admin.seats.store');
        Route::post('/bulk-create', [SeatAdminController::class, 'bulkCreate'])->name('admin.seats.bulkCreate');
        Route::post('/delete/{id}', [SeatAdminController::class, 'destroy'])->name('admin.seats.delete');
    });
});