<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('failed', 'Silakan login untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            return redirect()->route('dashboard')->with('failed', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}