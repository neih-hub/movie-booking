<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Showtime;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'movies'  => Movie::orderBy('title')->get(),
            'cinemas' => Cinema::with('rooms')->orderBy('name')->get(), // MUST HAVE
        ]);
    }

    // Lấy danh sách ngày chiếu theo phim + rạp
    public function getDates(Request $request)
    {
        $movieId = $request->movie_id;
        $cinemaId = $request->cinema_id;

        $dates = Showtime::where('movie_id', $movieId)
            ->whereHas('room', function ($q) use ($cinemaId) {
                $q->where('cinema_id', $cinemaId);
            })
            ->select('date_start')
            ->distinct()
            ->orderBy('date_start')
            ->pluck('date_start');

        return response()->json($dates);
    }

    // Lấy suất chiếu theo ngày
    public function searchShowtime(Request $request)
    {
        $showtimes = Showtime::with('room')
            ->where('movie_id', $request->movie_id)
            ->whereHas('room', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            })
            ->where('date_start', $request->date_start)
            ->orderBy('start_time')
            ->get();

        return response()->json($showtimes);
    }
    public function getRooms(Request $request)
{
    $rooms = Showtime::with('room')
        ->where('movie_id', $request->movie_id)
        ->whereHas('room', function ($q) use ($request) {
            $q->where('cinema_id', $request->cinema_id);
        })
        ->where('date_start', $request->date_start)
        ->select('room_id')
        ->distinct()
        ->get()
        ->map(function ($st) {
            return [
                'id' => $st->room->id,
                'name' => $st->room->name
            ];
        });

    return response()->json($rooms);
}

}