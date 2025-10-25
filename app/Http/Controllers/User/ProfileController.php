<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Trang hồ sơ
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Cập nhật hồ sơ
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone = $request->phone;

        // Nếu có đổi mật khẩu
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile.index')->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
