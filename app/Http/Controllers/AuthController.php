<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Tampilkan halaman login */
    public function showLogin()
    {
        return view('auth.login');
    }

    /** Proses login, redirect berdasarkan role */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau Password salah.'])->onlyInput('email');
        }

        $user = Auth::user();

        // Tolak user yang di-suspend
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi Admin.']);
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'admin'     => redirect()->route('dashboard.admin'),
            'karyawan'  => redirect()->route('dashboard.karyawan'),
            'kurir'     => redirect()->route('dashboard.kurir'),
            'pelanggan' => redirect()->route('dashboard.pelanggan'),
            default     => redirect()->route('login')->withErrors(['login' => 'Role tidak dikenali.']),
        };
    }

    /** Tampilkan halaman register */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Proses register — hanya untuk pelanggan publik */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname'    => 'required|string|max:100',
            'lastname'     => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'address'      => 'required|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'role'     => 'pelanggan',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.pelanggan')->with('success', 'Selamat datang, ' . $user->firstname . '!');
    }

    /** Logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
