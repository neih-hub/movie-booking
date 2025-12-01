<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;

class ShowtimeAdminController extends Controller
{
    // List all showtimes with filters
    public function list(Request $request)
    {
        $query = Showtime::with(['movie', 'room.cinema']);

        // Filter by movie
        if ($request->has('movie_id') && $request->movie_id) {
            $query->where('movie_id', $request->movie_id);
        }

        // Filter by cinema
        if ($request->has('cinema_id') && $request->cinema_id) {
            $query->whereHas('room', function ($q) use ($request) {
                $q->where('cinema_id', $request->cinema_id);
            });
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('start_time', $request->date);
        }

        $showtimes = $query->orderBy('start_time', 'desc')->paginate(15);

        // Get movies and cinemas for filter dropdowns
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::orderBy('name')->get();

        return view('admin.showtimes.list', compact('showtimes', 'movies', 'cinemas'));
    }

    // Show create form
    public function create()
    {
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::with('rooms')->orderBy('name')->get();

        return view('admin.showtimes.create', compact('movies', 'cinemas'));
    }

    // Store new showtime
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
        ]);

        Showtime::create($request->all());

        return redirect()->route('admin.showtimes.list')->with('success', 'Thêm suất chiếu thành công!');
    }

    // Show edit form
    public function edit($id)
    {
        $showtime = Showtime::with('room.cinema')->findOrFail($id);
        $movies = Movie::orderBy('title')->get();
        $cinemas = Cinema::with('rooms')->orderBy('name')->get();

        return view('admin.showtimes.edit', compact('showtime', 'movies', 'cinemas'));
    }

    // Update showtime
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

    // Delete showtime
    public function destroy($id)
    {
        $showtime = Showtime::findOrFail($id);
        $showtime->delete();

        return back()->with('success', 'Xóa suất chiếu thành công!');
    }
}
