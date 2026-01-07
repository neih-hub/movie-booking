<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Showtime;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Phim đang chiếu
        $nowShowing = Movie::orderBy('title')->get();

        // Phim sắp chiếu
        $comingSoon = Movie::where('release_date', '>', today())
            ->orderBy('release_date')
            ->get();

        // Góc điện ảnh – lấy 10 bài viết mới nhất
        $latestPosts = Post::published()
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return view('home.index', [
            'nowShowing'   => $nowShowing,
            'comingSoon'   => $comingSoon,
            'cinemas'      => Cinema::orderBy('name')->get(),
            'latestPosts'  => $latestPosts,   // 🔥 để hiển thị Góc điện ảnh
        ]);
    }

    public function getCinemasByMovie(Request $request)
    {
        try {
            $movieId = $request->movie_id;

            if (!$movieId) {
                return response()->json([]);
            }

            $cinemas = Cinema::whereHas('rooms.showtimes', function ($query) use ($movieId) {
                $query->where('movie_id', $movieId);
            })
                ->orderBy('name', 'asc')
                ->get(['id', 'name'])
                ->toArray();

            return response()->json($cinemas);
        } catch (\Exception $e) {
            logger()->error('Error in getCinemasByMovie: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getRooms(Request $request)
    {
        try {
            $cinemaId = $request->cinema_id;

            if (!$cinemaId) {
                return response()->json([]);
            }

            $rooms = \App\Models\Room::where('cinema_id', $cinemaId)
                ->orderBy('name', 'asc')
                ->get(['id', 'name'])
                ->toArray();

            return response()->json($rooms);
        } catch (\Exception $e) {
            logger()->error('Error in getRooms: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getDates(Request $request)
    {
        try {
            $movieId = $request->movie_id;
            $roomId  = $request->room_id;

            if (!$movieId || !$roomId) {
                return response()->json([]);
            }

            $dates = Showtime::where('movie_id', $movieId)
                ->where('room_id', $roomId)
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

    public function searchShowtime(Request $request)
    {
        try {
            $movieId   = $request->movie_id;
            $roomId    = $request->room_id;
            $dateStart = $request->date_start;

            if (!$movieId || !$roomId || !$dateStart) {
                return response()->json([]);
            }

            $showtimes = Showtime::where('movie_id', $movieId)
                ->where('room_id', $roomId)
                ->where('date_start', $dateStart)
                ->orderBy('start_time', 'asc')
                ->get()
                ->map(function ($showtime) {
                    return [
                        'id'         => $showtime->id,
                        'movie_id'   => $showtime->movie_id,
                        'room_id'    => $showtime->room_id,
                        'date_start' => $showtime->date_start,
                        'start_time' => substr($showtime->start_time, 0, 5), // HH:MM
                        'price'      => $showtime->price,
                    ];
                });

            return response()->json($showtimes);
        } catch (\Exception $e) {
            logger()->error('Error in searchShowtime: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}
