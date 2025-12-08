<?php

namespace App\Http\Controllers\Admin;

use App\Models\Seat;
use App\Models\Room;
use App\Models\Cinema;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeatAdminController extends Controller
{
    // danh sách ghế
    public function list()
    {
        $cinemas = Cinema::with('rooms.seats')->get();
        return view('admin.seats.list', compact('cinemas'));
    }

    // tạo ghế
    public function create()
    {
        $rooms = Room::with('cinema')->get();
        return view('admin.seats.create', compact('rooms'));
    }

    // lưu ghế
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'seat_number' => 'required|string|max:10',
            'row' => 'required|string|max:5',
            'type' => 'required|in:normal,vip',
        ]);

        Seat::create($request->all());

        return redirect()->route('admin.seats.list')->with('success', 'Ghế đã được tạo thành công!');
    }

    // tạo ghế theo hàng
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rows' => 'required|integer|min:1|max:26',
            'seats_per_row' => 'required|integer|min:1|max:50',
            'type' => 'required|in:normal,vip',
        ]);

        $room = Room::findOrFail($request->room_id);
        $rows = $request->rows;
        $seatsPerRow = $request->seats_per_row;
        $type = $request->type;

        for ($i = 0; $i < $rows; $i++) {
            $rowLetter = chr(65 + $i); // ASCII + i = A, B, C,...

            for ($j = 1; $j <= $seatsPerRow; $j++) {
                Seat::create([
                    'room_id' => $room->id,
                    'seat_number' => $rowLetter . $j,
                    'row' => $rowLetter,
                    'type' => $type,
                ]);
            }
        }

        $totalSeats = $rows * $seatsPerRow;
        return redirect()->route('admin.seats.list')->with('success', "Đã tạo {$totalSeats} ghế thành công!");
    }

    // xóa ghế
    public function destroy($id)
    {
        $seat = Seat::findOrFail($id);
        $seat->delete();

        return redirect()->route('admin.seats.list')->with('success', 'Ghế đã được xóa!');
    }
}