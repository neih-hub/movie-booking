<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Booking;
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

        // Get recent bookings
        $recentBookings = Booking::with(['user', 'showtime.movie'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get revenue (assuming bookings have a total_price field)
        $totalRevenue = Booking::sum('total_price') ?? 0;

        // Get popular movies (most booked)
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
            'totalRevenue',
            'popularMovies'
        ));
    }
}
