<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cinema;

class CinemaAdminController extends Controller
{
    // List all cinemas
    public function list()
    {
        $cinemas = Cinema::withCount('rooms')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.cinemas.list', compact('cinemas'));
    }

    // Show create form
    public function create()
    {
        return view('admin.cinemas.create');
    }

    // Store new cinema
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Cinema::create($request->all());

        return redirect()->route('admin.cinemas.list')->with('success', 'Thêm rạp chiếu thành công!');
    }

    // Show edit form
    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        return view('admin.cinemas.edit', compact('cinema'));
    }

    // Update cinema
    public function update(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $cinema->update($request->all());

        return back()->with('success', 'Cập nhật rạp chiếu thành công!');
    }

    // Delete cinema
    public function destroy($id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete();

        return back()->with('success', 'Xóa rạp chiếu thành công!');
    }
}
