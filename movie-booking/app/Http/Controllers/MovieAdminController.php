<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Str;

class MovieAdminController extends Controller
{
    // Danh sách phim
    public function list()
    {
        $movies = Movie::all();
        return view('admin.movies.list', compact('movies'));
    }

    // Form thêm
    public function create()
    {
        return view('admin.movies.create');
    }

    // Lưu phim mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'nullable|integer',
            'release_date' => 'nullable|date',
            'poster' => 'nullable|image'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title).'-'.time();

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move('uploads/posters', $name);
            $data['poster'] = 'uploads/posters/'.$name;
        }

        Movie::create($data);

        return redirect()->route('admin.movies.list')->with('success','Thêm phim thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    // Cập nhật
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'title'=>'required',
            'duration'=>'nullable|integer',
            'release_date'=>'nullable|date',
            'poster'=>'nullable|image'
        ]);

        $data = $request->all();

        if($request->hasFile('poster')){
            $file = $request->file('poster');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move('uploads/posters', $name);
            $data['poster'] = 'uploads/posters/'.$name;
        }

        $movie->update($data);

        return back()->with('success','Cập nhật thành công!');
    }

    // Xóa phim
    public function destroy($id)
    {
        Movie::destroy($id);
        return back()->with('success', 'Xóa thành công!');
    }
}