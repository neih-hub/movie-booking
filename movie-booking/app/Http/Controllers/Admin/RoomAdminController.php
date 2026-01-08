<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomAdminController extends Controller
{
    public function manage()
    {
        $cinemas = Cinema::with('rooms')->get();
        return view('admin.rooms.manage', compact('cinemas'));
    }

    public function showSeatsHoneycomb($id)
    {
        $room = Room::with(['seats', 'cinema'])->findOrFail($id);

        $seatsPerRow = $room->seats_per_row ?? 10; //mặc định 10 ghế/hàng
        $seatRows = [];

        foreach ($room->seats as $seat) {
            $number = (int) substr($seat->seat_number, 1);
            $rowIndex = ceil($number / $seatsPerRow) - 1;

            if (!isset($seatRows[$rowIndex])) {
                $seatRows[$rowIndex] = [];
            }
            $seatRows[$rowIndex][] = $seat;
        }

        foreach ($seatRows as &$row) {
            usort($row, function ($a, $b) {
                return strcmp($a->seat_number, $b->seat_number);
            });
        }


        //thông tin booking cho mỗi ghế
        $seatsWithBookings = [];
        foreach ($room->seats as $seat) {
            $bookingData = null;

            try {
                //tìm booking gần nhất cho ghế này
                $bookingSeat = \App\Models\BookingSeat::where('seat_id', $seat->id)
                    ->with(['booking.user'])
                    ->latest()
                    ->first();

                if ($bookingSeat && $bookingSeat->booking && $bookingSeat->booking->status == 1) {
                    $bookingData = [
                        'id' => $bookingSeat->booking->id,
                        'user' => [
                            'name' => $bookingSeat->booking->user->name ?? 'N/A',
                            'email' => $bookingSeat->booking->user->email ?? 'N/A',
                            'phone' => $bookingSeat->booking->user->phone ?? 'N/A',
                            'gender' => $bookingSeat->booking->user->gender ?? 'other',
                        ]
                    ];
                }
            } catch (\Exception $e) {
                $bookingData = null;
            }

            $seatsWithBookings[] = [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'room_name' => $room->name,
                'cinema_name' => $room->cinema->name,
                'booking' => $bookingData
            ];
        }

        return view('admin.rooms.seats_honeycomb', compact('room', 'seatRows', 'seatsWithBookings'));
    }
    public function list()
    {
        $rooms = Room::with('cinema')->orderBy('created_at', 'desc')->get();
        return view('admin.rooms.list', compact('rooms'));
    }

    public function create()
    {
        $cinemas = Cinema::all();
        return view('admin.rooms.create', compact('cinemas'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seats_per_row' => 'nullable|integer|min:1',
            'rows' => 'nullable|integer|min:1',
        ]);

        $room = Room::create($request->all());

        //tự tạo ghế cho room mới
        for ($i = 1; $i <= $request->total_seats; $i++) {
            $label = $room->name . str_pad($i, 2, '0', STR_PAD_LEFT);
            \App\Models\Seat::create([
                'room_id' => $room->id,
                'seat_number' => $label,
                'type' => 'normal',
            ]);
        }

        return redirect()->route('admin.rooms.manage')->with('success', 'Phòng chiếu đã được tạo thành công!');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $cinemas = Cinema::all();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',
            'rows' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($id);
        $oldTotalSeats = $room->seats()->count();
        $newTotalSeats = $request->total_seats;

        $room->update($request->all());

        //đồng bộ ghê
        if ($newTotalSeats > $oldTotalSeats) {
            for ($i = $oldTotalSeats + 1; $i <= $newTotalSeats; $i++) {
                $label = $room->name . str_pad($i, 2, '0', STR_PAD_LEFT);
                \App\Models\Seat::create([
                    'room_id' => $room->id,
                    'seat_number' => $label,
                    'type' => 'normal',
                ]);
            }
        } elseif ($newTotalSeats < $oldTotalSeats) {
            //xoá ghế dư
            $room->seats()->orderBy('seat_number', 'desc')
                ->limit($oldTotalSeats - $newTotalSeats)
                ->delete();
        }

        return redirect()->route('admin.rooms.manage')->with('success', 'Phòng chiếu đã được cập nhật!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.rooms.manage')->with('success', 'Phòng chiếu đã được xóa!');
    }
}