<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login()
    {
        return view('auth.login'); // pastikan view ini ada: resources/views/auth/login.blade.php
    }

    // Proses login
    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek role user dan redirect sesuai role
            $role = Auth::user()->role;
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'karyawan':
                    return redirect()->route('karyawan.dashboard');
                case 'kurir':
                    return redirect()->route('kurir.dashboard');
                case 'pelanggan':
                    return redirect()->route('dashboard.pelanggan');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors('Role tidak dikenali.');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Tampilkan halaman registrasi
    public function showRegisterForm()
    {
        return view('auth.register'); // pastikan view ini ada
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required',
            'address' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'role' => 'pelanggan', // default pelanggan
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
return redirect()->route('dashboard.pelanggan');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
