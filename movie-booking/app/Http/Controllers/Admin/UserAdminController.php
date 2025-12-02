<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserAdminController extends Controller
{
    // ============================
    // DANH SÁCH USER + TÌM KIẾM
    // ============================
    public function list(Request $request)
    {
        $query = User::query();

        // Search name + email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter role (0 = admin, 1 = user)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.list', compact('users'));
    }

    // ============================
    // FORM SỬA USER
    // ============================
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // ============================
    // CẬP NHẬT USER
    // ============================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $id,
            'role'      => 'required|in:0,1',
            'status'    => 'required|in:0,1',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'birthday'  => 'nullable|date',
            'gender'    => 'nullable|in:male,female,other',
        ]);

        $data = $request->only([
            'name', 'email', 'role', 'status',
            'phone', 'address', 'birthday', 'gender'
        ]);

        // Avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = time() . "_" . $file->getClientOriginalName();
            $file->move('uploads/avatars', $name);
            $data['avatar'] = 'uploads/avatars/' . $name;
        }

        // Update password (nếu nhập)
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);

            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật người dùng thành công!');
    }

    // ============================
    // KHÓA / MỞ USER
    // ============================
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 1 ? 0 : 1; // toggle
        $user->save();

        $msg = $user->status ? "Đã kích hoạt người dùng!" : "Đã khóa người dùng!";
        return back()->with('success', $msg);
    }

    // ============================
    // XÓA USER (KHÔNG CHO XÓA CHÍNH MÌNH)
    // ============================
    public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->id == Auth::id()) {
        return back()->with('error', 'Bạn không thể xóa tài khoản của chính bạn!');
    }

    $user->delete();

    return back()->with('success', 'Xóa người dùng thành công!');


        $user->delete();

        return back()->with('success', 'Xóa người dùng thành công!');
    }
}