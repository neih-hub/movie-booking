<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;
use App\Http\Controllers\Controller;

class ShowtimeAdminController extends Controller
{
    public function list(Request $request)
    {
        $query = Showtime::with(['movie', 'room.cinema']);

        if ($request->has('movie_id') && $request->movie_id) {
            $query->where('movie_id', $request->movie_id);
        }
        if ($request->has('cinema_id') && $request->cinema_id) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('start_time', $request->date);
        }

        $showtimes = $query->orderBy('start_time', 'desc')->paginate(15);

        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.showtimes.list', compact('showtimes', 'movies', 'cinemas'));
    }

    public function create()
    {
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::with('rooms')->orderBy('name')->get();

        return view('admin.showtimes.create', compact('movies', 'cinemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'movie_id' => 'required|exists:movies,id',
    'room_id' => 'required|exists:rooms,id',
    'date_start' => 'required|date',
    'start_time' => 'required|date_format:H:i',
    'price' => 'required|numeric|min:0',
]);


        Showtime::create($request->all());

        return redirect()->route('admin.showtimes.list')->with('success', 'Thêm suất chiếu thành công!');
    }

    public function edit($id)
    {
        $showtime = Showtime::with('room.cinema')->findOrFail($id);
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::with('rooms')->orderBy('name')->get();

        return view('admin.showtimes.edit', compact('showtime', 'movies', 'cinemas'));
    }

    public function update(Request $request, $id)
    {
        $showtime = Showtime::findOrFail($id);

        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        $showtime->update($request->all());

        return back()->with('success', 'Cập nhật suất chiếu thành công!');
    }

    public function destroy($id)
    {
        $showtime = Showtime::findOrFail($id);
        $showtime->delete();

        return back()->with('success', 'Xóa suất chiếu thành công!');
    }
}