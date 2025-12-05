<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// HOME
use App\Http\Controllers\HomeController;

// CLIENT
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// ADMIN
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\MovieAdminController;
use App\Http\Controllers\Admin\CinemaAdminController;
use App\Http\Controllers\Admin\RoomAdminController;
use App\Http\Controllers\Admin\SeatAdminController;
use App\Http\Controllers\Admin\ShowtimeAdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\FoodAdminController;

use App\Models\User;

/*
|--------------------------------------------------------------------------
| HOME PAGE
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// API load ngày chiếu
Route::get('/get-dates', [HomeController::class, 'getDates'])->name('showtime.dates');

// API load suất chiếu
Route::get('/search-showtime', [HomeController::class, 'searchShowtime'])->name('search.showtime');

// Trang mua vé (test — sau này thay bằng BookingController)
Route::get('/booking/{showtime}', function ($id) {
    return "Trang đặt vé cho suất chiếu: " . $id;
});
Route::get('/get-rooms', [HomeController::class, 'getRooms'])->name('showtime.rooms');



/*
|--------------------------------------------------------------------------
| MOVIES (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show'])
    ->where('id', '[0-9]+')
    ->name('movie.show');


/*
|--------------------------------------------------------------------------
| SHOWTIME (CLIENT)
|--------------------------------------------------------------------------
*/
Route::get('/showtime/{id}', [ShowtimeController::class, 'show']);


/*
|--------------------------------------------------------------------------
| BOOKING CLIENT
|--------------------------------------------------------------------------
*/
Route::post('/booking', [BookingController::class, 'store']);
Route::get('/booking', [BookingController::class, 'index'])
    ->middleware(['auth', 'profile.completed']);


/*
|--------------------------------------------------------------------------
| AJAX SEARCH MOVIE
|--------------------------------------------------------------------------
*/
Route::get('/search-movie', function (Request $req) {
    return \App\Models\Movie::where('title', 'like', "%{$req->query}%")
        ->take(5)
        ->get(['id', 'title']);
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Bạn đã đăng xuất!');
})->name('logout');


/*
|--------------------------------------------------------------------------
| USER PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
});


/*
|--------------------------------------------------------------------------
| CREATE DEFAULT ADMIN (ONE-TIME)
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // DASHBOARD
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserAdminController::class, 'list'])->name('list');
        Route::get('/edit/{id}', [UserAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserAdminController::class, 'update'])->name('update');
        Route::post('/toggle/{id}', [UserAdminController::class, 'toggleStatus'])->name('toggle');
        Route::post('/delete/{id}', [UserAdminController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | MOVIES
    |--------------------------------------------------------------------------
    */
    Route::prefix('movies')->name('movies.')->group(function () {
        Route::get('/', [MovieAdminController::class, 'list'])->name('list');
        Route::get('/create', [MovieAdminController::class, 'create'])->name('create');
        Route::post('/store', [MovieAdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MovieAdminController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [MovieAdminController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | CINEMAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('cinemas')->name('cinemas.')->group(function () {
        Route::get('/', [CinemaAdminController::class, 'list'])->name('list');
        Route::get('/create', [CinemaAdminController::class, 'create'])->name('create');
        Route::post('/store', [CinemaAdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CinemaAdminController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [CinemaAdminController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | ROOMS
    |--------------------------------------------------------------------------
    */
    Route::prefix('rooms')->name('rooms.')->group(function () {

        Route::get('/manage', [RoomAdminController::class, 'manage'])->name('manage');

        Route::get('/', [RoomAdminController::class, 'list'])->name('list');

        Route::get('/create', [RoomAdminController::class, 'create'])->name('create');
        Route::post('/store', [RoomAdminController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [RoomAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RoomAdminController::class, 'update'])->name('update');

        Route::post('/delete/{id}', [RoomAdminController::class, 'destroy'])->name('delete');

        Route::get('/{id}/seats-honeycomb',
            [RoomAdminController::class, 'showSeatsHoneycomb'])
            ->name('seats.honeycomb');
    });

    /*
    |--------------------------------------------------------------------------
    | SHOWTIMES
    |--------------------------------------------------------------------------
    */
    Route::prefix('showtimes')->name('showtimes.')->group(function () {
        Route::get('/', [ShowtimeAdminController::class, 'list'])->name('list');
        Route::get('/create', [ShowtimeAdminController::class, 'create'])->name('create');
        Route::post('/store', [ShowtimeAdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ShowtimeAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ShowtimeAdminController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [ShowtimeAdminController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | BOOKINGS
    |--------------------------------------------------------------------------
    */
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingAdminController::class, 'list'])->name('list');
        Route::get('/show/{id}', [BookingAdminController::class, 'show'])->name('show');
        Route::post('/cancel/{id}', [BookingAdminController::class, 'cancel'])->name('cancel');
        Route::post('/delete/{id}', [BookingAdminController::class, 'destroy'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | FOODS
    |--------------------------------------------------------------------------
    */
    Route::prefix('foods')->name('foods.')->group(function () {
        Route::get('/', [FoodAdminController::class, 'list'])->name('list');
        Route::get('/create', [FoodAdminController::class, 'create'])->name('create');
        Route::post('/create', [FoodAdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FoodAdminController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [FoodAdminController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [FoodAdminController::class, 'destroy'])->name('delete');
    });
});