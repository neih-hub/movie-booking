<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Showtime;
use Illuminate\Support\Facades\Log;

class CinemaController extends Controller
{
    public function show($cinema_id)
    {
        $cinema = Cinema::findOrFail($cinema_id);
        $cinemas = Cinema::orderBy('name')->get();
        \Log::info('Theater Page Debug', [
            'cinema_id' => $cinema_id,
            'current_date' => now()->toDateString(),
            'timezone' => config('app.timezone')
        ]);
        
        $showtimes = Showtime::whereHas('room', function($query) use ($cinema_id) {
                $query->where('cinema_id', $cinema_id);
            })
            ->with(['movie', 'room'])
            ->orderBy('date_start')
            ->orderBy('start_time')
            ->get();
        \Log::info('Showtimes found', [
            'count' => $showtimes->count(),
            'dates' => $showtimes->pluck('date_start')->unique()->toArray()
        ]);
        $groupedShowtimes = $showtimes->groupBy(function($showtime) {
            return $showtime->movie_id;
        })->map(function($movieShowtimes) {
            return $movieShowtimes->groupBy('date_start');
        });
        
        return view('theaters.show', compact('cinema', 'cinemas', 'groupedShowtimes'));
    }
}
