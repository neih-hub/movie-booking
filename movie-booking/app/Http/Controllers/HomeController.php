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
            'cinemas' => Cinema::orderBy('name')->get(),
        ]);
    }

    /**
     * Lấy danh sách ngày chiếu theo phim + rạp
     * 
     * @param Request $request (movie_id, cinema_id)
     * @return JSON array of dates
     */
    public function getDates(Request $request)
    {
        try {
            $movieId = $request->movie_id;
            $cinemaId = $request->cinema_id;

            if (!$movieId || !$cinemaId) {
                return response()->json([]);
            }

            // Lấy các ngày chiếu duy nhất cho phim tại rạp này
            $dates = Showtime::where('movie_id', $movieId)
                ->whereHas('room', function ($q) use ($cinemaId) {
                    $q->where('cinema_id', $cinemaId);
                })
                ->select('date_start')
                ->distinct()
                ->orderBy('date_start', 'asc')
                ->pluck('date_start')
                ->toArray();

            return response()->json($dates);
        } catch (\Exception $e) {
            logger()->error('Error in getDates: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Lấy suất chiếu theo phim + rạp + ngày
     * 
     * @param Request $request (movie_id, cinema_id, date_start)
     * @return JSON array of showtimes
     */
    public function searchShowtime(Request $request)
    {
        try {
            $movieId = $request->movie_id;
            $cinemaId = $request->cinema_id;
            $dateStart = $request->date_start;

            if (!$movieId || !$cinemaId || !$dateStart) {
                return response()->json([]);
            }

            // Lấy tất cả suất chiếu cho phim tại rạp vào ngày đã chọn
            $showtimes = Showtime::with(['room.cinema', 'movie'])
                ->where('movie_id', $movieId)
                ->whereHas('room', function ($q) use ($cinemaId) {
                    $q->where('cinema_id', $cinemaId);
                })
                ->where('date_start', $dateStart)
                ->orderBy('start_time', 'asc')
                ->get()
                ->map(function($showtime) {
                    return [
                        'id' => $showtime->id,
                        'movie_id' => $showtime->movie_id,
                        'room_id' => $showtime->room_id,
                        'date_start' => $showtime->date_start,
                        'start_time' => substr($showtime->start_time, 0, 5), // HH:MM format
                        'price' => $showtime->price,
                        'room' => [
                            'id' => $showtime->room->id,
                            'name' => $showtime->room->name,
                            'cinema_id' => $showtime->room->cinema_id,
                        ],
                        'movie' => [
                            'id' => $showtime->movie->id,
                            'title' => $showtime->movie->title,
                        ]
                    ];
                });

            return response()->json($showtimes);
        } catch (\Exception $e) {
            logger()->error('Error in searchShowtime: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
