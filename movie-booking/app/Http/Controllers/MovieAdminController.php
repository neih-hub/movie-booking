<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Str;


class MovieAdminController extends Controller
{
    // Danh sách
    public function list()
    {
        $movies = Movie::all();
        return view('admin.movies.list', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Movie::create($data);
        return redirect()->route('admin.movies.list')->with('success', 'Thêm phim thành công');
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($request->all());
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        Movie::destroy($id);
        return redirect()->back()->with('success', 'Xóa thành công');
    }
} 