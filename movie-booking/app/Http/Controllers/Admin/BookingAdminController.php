<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Controllers\Controller;

class BookingAdminController extends Controller
{
    //danh sách vé
    public function list(Request $request)
    {
        $query = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema']);

        // tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // bộ lọc theo phim
        if ($request->has('movie_id') && $request->movie_id) {
            $query->whereHas('showtime', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        // lọc theo ngày
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // lọc theo trạng thái 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.list', compact('bookings'));
    }

    // xem thông tin vé
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
// nút hủy vé: 
    public function cancel($id)
    {
        try {
            $booking = Booking::with('showtime.movie', 'user')->findOrFail($id);
            
            // hoạt động=1, hủy = 0;
            if ($booking->status == 1) {
                // hủy vé
                \Log::info('Cancelling booking', [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'user_name' => $booking->user->name ?? 'Unknown',
                    'movie' => $booking->showtime->movie->title ?? 'Unknown'
                ]);

                // thông báo hủy
                $notification = \App\Models\Notification::create([
                    'user_id' => $booking->user_id,
                    'type' => 'booking_cancelled',
                    'message' => "Vé xem phim {$booking->showtime->movie->title} của bạn vừa bị hủy bởi Quản trị viên",
                    'data' => [
                        'movie_id' => $booking->showtime->movie_id,
                        'movie_title' => $booking->showtime->movie->title,
                        'booking_id' => $booking->id
                    ]
                ]);
                
                \Log::info('Cancellation notification created', [
                    'notification_id' => $notification->id,
                    'for_user_id' => $notification->user_id
                ]);

                $booking->status = 0;
                $booking->save();

                return back()->with('success', 'Đã hủy đặt vé thành công và gửi thông báo đến user!');
                
            } else {
                // khôi phục vé
                \Log::info('Restoring booking', [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'user_name' => $booking->user->name ?? 'Unknown',
                    'movie' => $booking->showtime->movie->title ?? 'Unknown'
                ]);

                // thông báo khôi phục
                $notification = \App\Models\Notification::create([
                    'user_id' => $booking->user_id,
                    'type' => 'booking_restored',
                    'message' => "Vé xem phim {$booking->showtime->movie->title} của bạn được khôi phục bởi Quản trị viên",
                    'data' => [
                        'movie_id' => $booking->showtime->movie_id,
                        'movie_title' => $booking->showtime->movie->title,
                        'booking_id' => $booking->id
                    ]
                ]);
                
                \Log::info('Restoration notification created', [
                    'notification_id' => $notification->id,
                    'for_user_id' => $notification->user_id
                ]);

                $booking->status = 1;
                $booking->save();

                return back()->with('success', 'Đã khôi phục đặt vé thành công và gửi thông báo đến user!');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error toggling booking status: ' . $e->getMessage(), [
                'booking_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi khi thay đổi trạng thái vé: ' . $e->getMessage());
        }
    }

    // xóa vé
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Xóa đặt vé thành công!');
    }
}