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
        // Get statistics
        $totalUsers = User::count();
        $totalMovies = Movie::count();
        $totalCinemas = Cinema::count();
        $totalBookings = Booking::count();
        $totalFoods = Food::count();
        $totalPosts = Post::count();

        // Get recent bookings (safe eager load)
        $recentBookings = Booking::with(['user', 'showtime.movie'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Revenue — if column name differs, this will just return 0
        $totalRevenue = Booking::sum('total_price') ?? 0;

        // Popular movies (example)
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