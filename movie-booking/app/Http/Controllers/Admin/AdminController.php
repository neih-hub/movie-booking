<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Booking;
use App\Models\Food;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // thống kê
        $totalUsers = User::count();
        $totalMovies = Movie::count();
        $totalCinemas = Cinema::count();
        $totalBookings = Booking::count();
        $totalFoods = Food::count();
        $totalPosts = Post::count();

        // đặt chỗ ngồi gần đây
        $recentBookings = Booking::with(['user', 'showtime.movie'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // doanh thu - nếu tên cột không khớp thì trả về 0
        $totalRevenue = Booking::sum('total_price') ?? 0;

        // phim phổ biến
        $popularMovies = Movie::withCount('showtimes')
            ->orderBy('showtimes_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMovies',
            'totalCinemas',
            'totalBookings',
            'recentBookings',
            'totalFoods',
            'totalPosts',
        ));
    }
}