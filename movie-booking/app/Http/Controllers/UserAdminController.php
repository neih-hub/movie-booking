<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserAdminController extends Controller
{
    public function list()
    {
        $users = User::all();
        return view('admin.users.list', compact('users'));
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Xóa user thành công');
    }
}