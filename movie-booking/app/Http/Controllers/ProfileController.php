<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->with([
                'showtime.movie',
                'showtime.room.cinema',
                'bookingSeats.seat',
                'bookingFoods.food'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.profile', compact('user', 'bookings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update($request->only('name', 'phone', 'address', 'birthday', 'gender'));

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $file = $request->file('avatar');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/avatar'), $fileName);

        /** @var User $user */
        $user = Auth::user();
        $user->avatar = 'uploads/avatar/' . $fileName;
        $user->save();

        return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }
}