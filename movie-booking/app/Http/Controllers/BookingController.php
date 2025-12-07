<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\BookingFood;
use App\Models\Food;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Display booking form
    public function create($showtime_id)
    {
        $showtime = Showtime::with(['movie', 'room.cinema', 'room.seats'])
            ->findOrFail($showtime_id);

        return view('bookings.create', compact('showtime'));
    }

    // Get available seats for a showtime (API)
    public function getSeats($showtime_id)
    {
        $showtime = Showtime::with('room.seats')->findOrFail($showtime_id);
        
        // Get all booked seat IDs for this showtime
        $bookedSeatIds = BookingSeat::whereHas('booking', function($query) use ($showtime_id) {
            $query->where('showtime_id', $showtime_id);
        })->pluck('seat_id')->toArray();

        $seats = $showtime->room->seats->map(function($seat) use ($bookedSeatIds) {
            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'type' => $seat->type,
                'is_occupied' => in_array($seat->id, $bookedSeatIds)
            ];
        });

        return response()->json([
            'seats' => $seats,
            'price' => $showtime->price
        ]);
    }

    // Get available foods (API)
    public function getFoods()
    {
        $foods = Food::where('total', '>', 0)->get();
        return response()->json($foods);
    }

    // Store booking
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
            'foods' => 'nullable|array',
            'foods.*.id' => 'exists:foods,id',
            'foods.*.quantity' => 'integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $showtime = Showtime::findOrFail($request->showtime_id);
            
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt vé!');
            }

            // Calculate total price
            $seatPrice = $showtime->price * count($request->seat_ids);
            $foodPrice = 0;

            if ($request->has('foods') && is_array($request->foods)) {
                foreach ($request->foods as $foodItem) {
                    $food = Food::find($foodItem['id']);
                    if ($food) {
                        $foodPrice += $food->price * $foodItem['quantity'];
                    }
                }
            }

            $totalPrice = $seatPrice + $foodPrice;

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'showtime_id' => $request->showtime_id,
                'total_price' => $totalPrice,
                'status' => 1 // Active
            ]);

            // Create booking seats
            foreach ($request->seat_ids as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $showtime->price
                ]);
            }

            // Create booking foods
            if ($request->has('foods') && is_array($request->foods)) {
                foreach ($request->foods as $foodItem) {
                    $food = Food::find($foodItem['id']);
                    if ($food) {
                        BookingFood::create([
                            'booking_id' => $booking->id,
                            'food_id' => $foodItem['id'],
                            'quantity' => $foodItem['quantity'],
                            'price' => $food->price
                        ]);

                        // Update food stock
                        $food->decrement('total', $foodItem['quantity']);
                    }
                }
            }

            DB::commit();

            return redirect()->route('booking.success', $booking->id)
                ->with('success', 'Đặt vé thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Success page
    public function success($id)
    {
        $booking = Booking::with([
            'showtime.movie',
            'showtime.room.cinema',
            'bookingSeats.seat',
            'bookingFoods.food'
        ])->findOrFail($id);

        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.success', compact('booking'));
    }
}