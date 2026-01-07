<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Cinema;

class ShowtimeController extends Controller
{
    public function show($id)
    {
        $showtime = Showtime::with(['movie', 'room.cinema'])
            ->findOrFail($id);

        return view('showtime.show', compact('showtime'));
    }

    public function getShowtimesByMovie($movieId)
    {
        $movie = Movie::findOrFail($movieId);

        $showtimes = Showtime::with(['room', 'room.cinema'])
            ->where('movie_id', $movieId)
            ->orderBy('date_start', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'movie' => $movie,
            'showtimes' => $showtimes,
        ]);
    }
    public function filter(Request $request)
    {
        $movieId = $request->movie_id;
        $cinemaId = $request->cinema_id;
        $date = $request->date_start;

        $query = Showtime::with(['room', 'movie', 'room.cinema']);

        if ($movieId) {
            $query->where('movie_id', $movieId);
        }

        if ($cinemaId) {
            $query->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            });
        }

        if ($date) {
            $query->where('date_start', $date);
        }

        $showtimes = $query->orderBy('start_time', 'asc')->get();

        return response()->json($showtimes);
    }
}
