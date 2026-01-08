<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserAdminController extends Controller
{
    public function list(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.list', compact('users'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $data = $request->only([
            'name',
            'email',
            'role',
            'status',
            'phone',
            'address',
            'birthday',
            'gender'
        ]);
        // cập nhật avt
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = time() . "_" . $file->getClientOriginalName();
            $file->move('uploads/avatars', $name);
            $data['avatar'] = 'uploads/avatars/' . $name;
        }

        //cập nhật password
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);

            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật người dùng thành công!');
    }
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 1 ? 0 : 1;// 1=bật 0 = tắt
        $user->save();

        $msg = $user->status ? "Đã kích hoạt người dùng!" : "Đã khóa người dùng!";
        return back()->with('success', $msg);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính bạn!');
        }

        $user->delete();

        return back()->with('success', 'Xóa người dùng thành công!');
    }
}