<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Http\Controllers\Controller;

class MovieAdminController extends Controller
{
    // danh sách phim
   public function list()
{
    $movies = Movie::all();
    return view('admin.movies.list', compact('movies'));
}


    public function create()
    {
        return view('admin.movies.create');
    }

    // lưu phim
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->genre) {
            $data['genre'] = array_map('trim', explode(',', $request->genre));
        } else {
            $data['genre'] = [];
        }

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move('uploads/posters', $name);
            $data['poster'] = 'uploads/posters/'.$name;
        }

        Movie::create($data);

        return redirect()->route('admin.movies.list')
            ->with('success', 'Thêm phim thành công!');
    }

    // chỉnh sửa phim
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    // cập nhật phim
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $data = $request->all();
        if ($request->genre) {
            $data['genre'] = array_map('trim', explode(',', $request->genre));
        } else {
            $data['genre'] = [];
        }

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move('uploads/posters', $name);
            $data['poster'] = 'uploads/posters/'.$name;
        }

        $movie->update($data);

        return back()->with('success', 'Cập nhật phim thành công!');
    }

    // xóa phim
    public function destroy($id)
    {
        Movie::destroy($id);
        return back()->with('success', 'Xóa phim thành công!');
    }
}