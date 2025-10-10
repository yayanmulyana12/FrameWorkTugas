<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (Auth::check()) {
            // Cek apakah role user cocok dengan salah satu role yang diizinkan
            foreach ($roles as $role) {
                if (Auth::user()->role === $role) {
                    return $next($request);
                }
            }

            // Jika role tidak cocok, arahkan ke dashboard (tanpa logout)
            return redirect()
                ->route('dashboard')
                ->with('status', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Jika belum login, arahkan ke halaman login
        return redirect()->route('login');
    }
}
