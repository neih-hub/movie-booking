<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('profile.notifications', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);
            
        $notification->update(['is_read' => true]);
        
        return back();
    }
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }
    public function unreadCount()
    {
        $count = auth()->user()
            ->notifications()
            ->unread()
            ->count();
            
        return response()->json(['count' => $count]);
    }
}
