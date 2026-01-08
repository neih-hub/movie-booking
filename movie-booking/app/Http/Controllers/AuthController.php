<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //hiển thị đăng kí
    public function showRegister()
    {
        return view('auth.register');
    }

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
                'role' => 1,
                'status' => 1,
            ]);

            Auth::login($user);
            return redirect('/profile')->with('success', 'Đăng ký thành công! Vui lòng hoàn thiện thông tin cá nhân.');
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Đăng ký thất bại: ' . $e->getMessage()])->withInput();
        }
    }

    //hiển thị đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (!Auth::user()->name || !Auth::user()->phone || !Auth::user()->address) {
                return redirect('/profile');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Sai email hoặc mật khẩu']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showProfile()
    {
        return view('auth.profile');
    }
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