<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // pastikan model User sudah ada dan sesuaikan

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cek login dengan username (bukan email default Laravel)
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Redirect berdasarkan role user
            $role = Auth::user()->role;
            switch ($role) {
                case 'admin':
                    return redirect()->intended('/admin_dashboard');
                case 'karyawan':
                    return redirect()->intended('/karyawan_dashboard');
                case 'kurir':
                    return redirect()->intended('/kurir_dashboard');
                case 'pelanggan':
                    return redirect()->intended('/pelanggan_dashboard');
                default:
                    return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'error' => 'Username atau Password salah!',
        ])->onlyInput('username');
    }

    // Tampilkan halaman registrasi
    public function showRegist()
    {
        return view('regist');
    }

    // Proses registrasi
    public function regist(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'role' => 'required|in:karyawan,kurir,pelanggan',
            'password' => 'required|string|min:6|confirmed', // password_confirmation harus ada di form
            'no_telepon' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ], [
            'role.in' => 'Role admin tidak bisa dipilih!',
        ]);

        // Buat user baru
        $user = User::create([
            'username' => $validated['username'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'no_telepon' => $validated['no_telepon'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
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
