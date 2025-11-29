<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Lấy email và mật khẩu từ form
        $credentials = $request->only('email', 'password');

        // Kiểm tra đăng nhập
        if (Auth::attempt($credentials)) {

            // Nếu user chưa cập nhật thông tin
            if (Auth::user()->name == null || Auth::user()->phone == null) {
                return redirect('/profile');
            }

            // Đăng nhập thành công -> chuyển về trang chủ
            return redirect('/');
        }

        // Sai mật khẩu hoặc email
        return back()->withErrors([
            'email' => 'Sai email hoặc mật khẩu'
        ]);
    }
}