<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserProfile
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Kiểm tra nếu chưa cập nhật thông tin
        if (!$user->name || !$user->phone) {
            return redirect('/profile')
                ->with('error', 'Bạn phải cập nhật thông tin cá nhân trước.');
        }

        return $next($request);
    }
}