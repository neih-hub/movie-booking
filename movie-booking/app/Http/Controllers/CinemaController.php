<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Showtime;

class CinemaController extends Controller
{
    /**
     * Display cinema details with showtimes
     */
    public function show($cinema_id)
    {
        // Load cinema info
        $cinema = Cinema::findOrFail($cinema_id);
        
        // Get all cinemas for dropdown
        $cinemas = Cinema::orderBy('name')->get();
        
        // Get showtimes for this cinema (upcoming only)
        $showtimes = Showtime::whereHas('room', function($query) use ($cinema_id) {
                $query->where('cinema_id', $cinema_id);
            })
            ->with(['movie', 'room'])
            ->where('date_start', '>=', now()->toDateString())
            ->orderBy('date_start')
            ->orderBy('start_time')
            ->get();
        
        // Group by movie and date
        $groupedShowtimes = $showtimes->groupBy(function($showtime) {
            return $showtime->movie_id;
        })->map(function($movieShowtimes) {
            return $movieShowtimes->groupBy('date_start');
        });
        
        return view('theaters.show', compact('cinema', 'cinemas', 'groupedShowtimes'));
    }
}
