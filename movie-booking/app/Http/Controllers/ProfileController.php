<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // HIỂN THỊ TRANG PROFILE
    public function index()
    {
        $user = Auth::user();
        // đúng là "view", không phải "views"
        return view('profile.profile', compact('user'));
    }

    // CẬP NHẬT THÔNG TIN CÁ NHÂN
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user = Auth::user();
        $user->update($request->only('name','phone','address','birthday','gender'));

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // CẬP NHẬT ẢNH ĐẠI DIỆN
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $file = $request->file('avatar');
        $fileName = time().'_'.$file->getClientOriginalName();

        // Lưu vào public/uploads/avatar (đảm bảo thư mục tồn tại và có quyền ghi)
        $file->move(public_path('uploads/avatar'), $fileName);

        $user = Auth::user();

        // Nếu bạn muốn xóa avatar cũ (nếu có), uncomment dòng sau
        // if ($user->avatar && file_exists(public_path($user->avatar))) { unlink(public_path($user->avatar)); }

        $user->avatar = 'uploads/avatar/'.$fileName;
        $user->save();

        return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }
}