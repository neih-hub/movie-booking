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
        try {
            $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 1, // Default role is user
                'status' => 1, // Default status is active
            ]);

            // Log user in after registration
            Auth::login($user);

            // Redirect to profile to complete registration
            return redirect('/profile')->with('success', 'Đăng ký thành công! Vui lòng hoàn thiện thông tin cá nhân.');
        } catch (\Exception $e) {
            // Log error to Laravel log file
            \Log::error('Registration failed: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Đăng ký thất bại: ' . $e->getMessage()])->withInput();
        }
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
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect('/')->with('success', 'Cập nhật thông tin thành công!');
    }
}