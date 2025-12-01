<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // ============================
    // DANH SÁCH PHIM
    // ============================
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->get();
        return view('movies.index', compact('movies'));
    }

    // ============================
    // XEM CHI TIẾT PHIM THEO SLUG
    // ============================
    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();

        return view('movies.show', compact('movie'));
    }

    // ============================
    // TÌM KIẾM NHANH GỢI Ý (AJAX)
    // ============================
    public function search(Request $req)
    {
        $query = $req->query;

        $movies = Movie::where('title', 'like', "%$query%")
            ->take(5)
            ->get(['title', 'slug']);

        return response()->json($movies);
    }
}