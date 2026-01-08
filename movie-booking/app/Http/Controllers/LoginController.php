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
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->name == null || Auth::user()->phone == null) {
                return redirect('/profile');
            }
            return redirect('/');
        }
        return back()->withErrors([
            'email' => 'Sai email hoặc mật khẩu'
        ]);
    }
}