<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Tampilkan halaman login
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Tampilkan halaman register
     *
     * @return \Illuminate\View\View
     */
    public function register(Request $request)
    {
        return view('auth.register');
    }

    /**
     * Proses login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login_process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,karyawan,kurir,pelanggan'
        ]);

        // Coba autentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Validasi role yang dipilih sesuai dengan role user
            if ($user->role !== $request->role) {
                Auth::logout();
                return redirect()->route('login')->with('failed', 'Role yang Anda pilih tidak sesuai dengan akun Anda.');
            }

            // Redirect sesuai role
            return match ($user->role) {
                'admin' => redirect()->route('dashboard.admin'),
                'karyawan' => redirect()->route('dashboard.karyawan'),
                'kurir' => redirect()->route('dashboard.kurir'),
                'client' => redirect()->route('dashboard.pelanggan'),
                default => redirect()->route('login')->with('failed', 'Role tidak valid.'),
            };
        }

        return redirect()->route('login')->with('failed', 'Email atau Password salah.');
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Proses register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_process(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:pelanggan,karyawan,kurir' // Hanya bisa pilih dari ini
        ]);

        $data = [
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname'],
            'phone_number' => $validatedData['phone_number'],
            'address' => $validatedData['address'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role']
        ];

        try {
            $user = User::create($data);
            Auth::login($user);

            // Redirect otomatis sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'karyawan':
                    return redirect()->route('dashboard.karyawan');
                case 'kurir':
                    return redirect()->route('dashboard.kurir');
                case 'client':
                    return redirect()->route('dashboard.pelanggan');
                default:
                    return redirect()->route('dashboard.pelanggan');
            }
        } catch (\Exception $e) {
            return redirect()->route('register')->with('failed', 'Gagal Membuat Akun');
        }
    }
}
