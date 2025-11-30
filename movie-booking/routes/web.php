<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieAdminController;
use App\Http\Controllers\UserAdminController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// ==========================
// TRANG CHỦ
// ==========================
Route::get('/', function () {
    return view('home.index', ['title' => 'Trang chủ']);
});

// ==========================
// MOVIES
// ==========================
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show']);

// ==========================
// SHOWTIME
// ==========================
Route::get('/showtime/{id}', [ShowtimeController::class, 'show']);

// ==========================
// BOOKING
// ==========================
Route::post('/booking', [BookingController::class, 'store']);

// ==========================
// SEARCH
// ==========================
Route::get('/search-movie', function (Request $req) {
    $query = $req->query;

    $movies = \App\Models\Movie::where('title', 'like', "%$query%")
        ->take(5)
        ->get(['id', 'title']);

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
    return redirect('/');
})->name('logout');

// ==========================
// PROFILE
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});

// ==========================
// TẠO ADMIN BẰNG ROUTE
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
// ADMIN PANEL (CHỈ ADMIN)
// ==========================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // QUẢN LÝ PHIM
    Route::prefix('/admin/movies')->group(function () {
        Route::get('/', [MovieAdminController::class, 'list'])->name('admin.movies.list');
        Route::get('/create', [MovieAdminController::class, 'create'])->name('admin.movies.create');
        Route::post('/store', [MovieAdminController::class, 'store'])->name('admin.movies.store');
        Route::get('/edit/{id}', [MovieAdminController::class, 'edit'])->name('admin.movies.edit');
        Route::post('/update/{id}', [MovieAdminController::class, 'update'])->name('admin.movies.update');
        Route::post('/delete/{id}', [MovieAdminController::class, 'destroy'])->name('admin.movies.delete');
    });

    // QUẢN LÝ USER
    Route::prefix('/admin/users')->group(function () {
        Route::get('/', [UserAdminController::class, 'list'])->name('admin.users.list');
        Route::post('/delete/{id}', [UserAdminController::class, 'destroy'])->name('admin.users.delete');
    });
});