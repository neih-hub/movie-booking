<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// CLIENT CONTROLLERS
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// ADMIN CONTROLLERS
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

/*
|--------------------------------------------------------------------------
| AJAX API (Client & Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {

    // Lấy phòng theo rạp
    Route::get('/rooms', [HomeController::class, 'getRooms'])->name('rooms');

    // Lấy ngày chiếu theo phim + phòng
    Route::get('/dates', [HomeController::class, 'getDates'])->name('dates');

    // Lấy suất chiếu theo ngày + phòng + phim
    Route::get('/showtimes', [HomeController::class, 'searchShowtime'])->name('showtimes');

    // Auto complete tìm phim
    Route::get('/search-movie', function (Request $req) {
        return \App\Models\Movie::where('title', 'like', "%{$req->query}%")
            ->take(5)
            ->get(['id', 'title']);
    })->name('search-movie');

    // Get seats for showtime
    Route::get('/seats/{showtime_id}', [BookingController::class, 'getSeats'])
        ->name('seats');

    // Get available foods
    Route::get('/foods', [BookingController::class, 'getFoods'])
        ->name('foods');

});

/*
|--------------------------------------------------------------------------
| BOOKING CLIENT
|--------------------------------------------------------------------------
*/

Route::get('/booking/{showtime_id}', [BookingController::class, 'create'])
    ->whereNumber('showtime_id')
    ->name('booking.create');

Route::post('/booking', [BookingController::class, 'store'])
    ->middleware('auth')
    ->name('booking.store');

Route::get('/booking/success/{id}', [BookingController::class, 'success'])
    ->middleware('auth')
    ->whereNumber('id')
    ->name('booking.success');


/*
|--------------------------------------------------------------------------
| MOVIES CLIENT
|--------------------------------------------------------------------------
*/

Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');

Route::get('/movie/{id}', [MovieController::class, 'show'])
    ->whereNumber('id')
    ->name('movie.show');

/*
|--------------------------------------------------------------------------
| POSTS CLIENT
|--------------------------------------------------------------------------
*/

Route::get('/posts', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');

Route::get('/post/{id}', [App\Http\Controllers\PostController::class, 'show'])
    ->whereNumber('id')
    ->name('post.show');

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
| CREATE DEFAULT ADMIN (one time)
|--------------------------------------------------------------------------
*/

Route::get('/create-admin', function () {
    $admin = User::create([
        'name'     => 'Administrator',
        'email'    => 'admin@gmail.com',
        'password' => Hash::make('admin123'),
        'role'     => 0,
        'status'   => 1,
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
        // ===================== POSTS (NEW) =====================
Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PostAdminController::class, 'list'])->name('list');
    Route::get('/create', [App\Http\Controllers\Admin\PostAdminController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Admin\PostAdminController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\Admin\PostAdminController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\Admin\PostAdminController::class, 'update'])->name('update');
    Route::post('/delete/{id}', [App\Http\Controllers\Admin\PostAdminController::class, 'destroy'])->name('delete');
    Route::post('/upload-image', [App\Http\Controllers\Admin\PostAdminController::class, 'uploadImage'])
        ->name('upload-image');
});


    // Dashboard
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

        Route::get('/', [RoomAdminController::class, 'list'])->name('list');
        Route::get('/create', [RoomAdminController::class, 'create'])->name('create');
        Route::post('/store', [RoomAdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RoomAdminController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RoomAdminController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [RoomAdminController::class, 'destroy'])->name('delete');

        Route::get('/manage', [RoomAdminController::class, 'manage'])->name('manage');
        Route::get('/{id}/seats-honeycomb', [RoomAdminController::class, 'showSeatsHoneycomb'])
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
