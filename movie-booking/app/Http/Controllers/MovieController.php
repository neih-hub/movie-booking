<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->get();
        return view('movies.index', compact('movies'));
    }

    public function show($id)
    {
        $movie = Movie::with(['showtimes' => function($query) {
            $query->orderBy('date_start')
                  ->orderBy('start_time')
                  ->with(['room.cinema']);
        }])->findOrFail($id);
        
        return view('movies.show', compact('movie'));
    }

    public function search(Request $req)
    {
        $query = $req->query;

        $movies = Movie::where('title', 'like', "%$query%")
            ->take(5)
            ->get(['id', 'title']);

        return response()->json($movies);
    }
}