<?php

namespace App\Http\Controllers;


class AuthController extends Controller
{
    // Tampilkan halaman login
            }
        }

        return back()->withErrors([

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
