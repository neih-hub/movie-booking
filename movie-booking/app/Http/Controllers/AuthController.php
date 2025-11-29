<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // =========================
    // HIỂN THỊ FORM ĐĂNG KÝ
    // =========================
    public function showRegister()
    {
        return view('auth.register');
    }

    // =========================
    // XỬ LÝ ĐĂNG KÝ
    // =========================
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/profile');
    }

    // =========================
    // HIỂN THỊ FORM ĐĂNG NHẬP
    // =========================
    public function showLogin()
    {
        return view('auth.login');
    }

    // =========================
    // XỬ LÝ ĐĂNG NHẬP
    // =========================
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            // Nếu chưa cập nhật tên, phone hoặc address → bắt cập nhật
            if (!Auth::user()->name || !Auth::user()->phone || !Auth::user()->address) {
                return redirect('/profile');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Sai email hoặc mật khẩu']);
    }

    // =========================
    // ĐĂNG XUẤT
    // =========================
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    // =========================
    // HIỂN THỊ TRANG PROFILE
    // =========================
    public function showProfile()
    {
        return view('auth.profile');
    }
    // CẬP NHẬT THÔNG TIN CÁ NHÂN
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Auth::user()->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect('/')->with('success', 'Cập nhật thông tin thành công!');
    }
}