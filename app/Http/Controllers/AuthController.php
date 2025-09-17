<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'phone' => $data['phone'],
        'address' => $data['address'],
        'role' => 'user',
    ]);

    $user->sendEmailVerificationNotification();
    Auth::login($user);

    return redirect()->route('verification.notice')
        ->with('success', 'Vui lòng kiểm tra email để xác thực tài khoản.');
}

    // Hiển thị form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Nếu chưa xác thực email
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('warning', 'Bạn cần xác thực email trước khi đăng nhập.');
            }

            // Redirect theo role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
