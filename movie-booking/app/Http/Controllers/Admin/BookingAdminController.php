<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Controllers\Controller;

class BookingAdminController extends Controller
{
    // List all bookings with search and filters
    public function list(Request $request)
    {
        $query = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema']);

        // Search by user name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by movie
        if ($request->has('movie_id') && $request->movie_id) {
            $query->whereHas('showtime', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.list', compact('bookings'));
    }

    // Show booking details
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'showtime.movie',
            'showtime.room.cinema',
            'bookingSeats.seat',
            'bookingFoods.food'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    // Cancel booking
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        // Update status to cancelled (assuming 0 = cancelled)
        $booking->status = 0;
        $booking->save();

        return back()->with('success', 'Đã hủy đặt vé thành công!');
    }

    // Delete booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Xóa đặt vé thành công!');
    }
}