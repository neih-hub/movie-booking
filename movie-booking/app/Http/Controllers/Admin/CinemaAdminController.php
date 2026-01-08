<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Seat;
use App\Http\Controllers\Controller;


class CinemaAdminController extends Controller
{
    public function list()
    {
        $cinemas = Cinema::withCount('rooms')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.cinemas.list', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $cinema = Cinema::create($request->only(['name', 'address', 'city']));
        //mỗi khi tạo 1 rạp chiếu thì tạo 3 phòng chiếu và 30 ghế cho mỗi phòng chiếu
        //3 phòng A,B,B mỗi khi tạo 1 rạp
        $rooms = ['A', 'B', 'C'];

        foreach ($rooms as $r) {
            $room = Room::create([
                'cinema_id' => $cinema->id,
                'name' => $r,
                'total_seats' => 30,
            ]);

            //tạo ghế từ 1 tới 30
            for ($i = 1; $i <= 30; $i++) {
                $label = $r . str_pad($i, 2, '0', STR_PAD_LEFT);
                Seat::create([
                    'room_id' => $room->id,
                    'seat_number' => $label,  //A01, A02
                    'type' => 'normal',
                ]);
            }
        }

        return redirect()->route('admin.cinemas.list')
            ->with('success', 'Thêm rạp chiếu thành công!');
    }

    //hiển thị danh sách phòng bên trong
    public function edit($id)
    {
        $cinema = Cinema::findOrFail($id);
        $rooms = Room::where('cinema_id', $id)->with('seats')->get();

        return view('admin.cinemas.edit', compact('cinema', 'rooms'));
    }
    public function showSeats($room_id)
    {
        $room = Room::with('seats')->findOrFail($room_id);
        return view('admin.cinemas.seats', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $cinema = Cinema::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $cinema->update($request->only(['name', 'address', 'city']));

        return back()->with('success', 'Cập nhật rạp chiếu thành công!');
    }

    public function destroy($id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete(); //xóa phòng voiws ghế luôn

        return back()->with('success', 'Xóa rạp chiếu thành công!');
    }
}