<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat;


class CinemaAdminController extends Controller
{
    // ======================
    // LIST CINEMAS
    // ======================
    public function list()
    {
        $cinemas = Cinema::withCount('rooms')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.cinemas.list', compact('cinemas'));
    }

    // ======================
    // CREATE FORM
    // ======================
    public function create()
    {
        return view('admin.cinemas.create');
    }

    // ======================
    // STORE CINEMA + CREATE 3 ROOMS + 10 SEATS EACH
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city'    => 'required|string|max:255',
        ]);

        // Create cinema
        $cinema = Cinema::create($request->only(['name', 'address', 'city']));

        // Default rooms: A, B, C
        $rooms = ['A', 'B', 'C'];

        foreach ($rooms as $r) {
            $room = Room::create([
                'cinema_id'   => $cinema->id,
                'name'        => $r,
                'total_seats' => 10,
            ]);

            // Generate seats A01...A10
            for ($i = 1; $i <= 10; $i++) {
                $label = $r . str_pad($i, 2, '0', STR_PAD_LEFT);
                Seat::create([
    'room_id'      => $room->id,
    'seat_number'  => $label,  // A01, A02...C10
    'type'         => 'normal', // hoặc VIP nếu bạn cần
]);
            }
        }

        return redirect()->route('admin.cinemas.list')
            ->with('success', 'Thêm rạp chiếu thành công!');
    }

    // ======================
    // EDIT FORM (SHOW ROOMS INSIDE)
    // ======================
    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        $rooms  = Room::where('cinema_id', $id)->with('seats')->get();

        return view('admin.cinemas.edit', compact('cinema', 'rooms'));
    }
    public function showSeats($room_id)
{
    $room = Room::with('seats')->findOrFail($room_id);
    return view('admin.cinemas.seats', compact('room'));
}


    // ======================
    // UPDATE CINEMA
    // ======================
    public function update(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city'    => 'required|string|max:255',
        ]);

        $cinema->update($request->only(['name', 'address', 'city']));

        return back()->with('success', 'Cập nhật rạp chiếu thành công!');
    }

    // ======================
    // DELETE CINEMA
    // ======================
    public function destroy($id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete(); // sẽ xóa luôn rooms + seats nhờ cascade

        return back()->with('success', 'Xóa rạp chiếu thành công!');
    }
}