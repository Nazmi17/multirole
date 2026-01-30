<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek apakah user login DAN punya 2FA secret
        if ($user && $user->google2fa_secret) {
            // Jika belum terverifikasi di sesi ini
            if (!session()->has('2fa_verified')) {
                // Biarkan lewat jika sedang mengakses halaman verifikasi OTP (biar ga loop)
                if ($request->routeIs('2fa.challenge') || $request->routeIs('2fa.verify')) {
                    return $next($request);
                }
                
                // Selain itu, lempar ke halaman OTP
                return redirect()->route('2fa.challenge');
            }
        }

        return $next($request);
    }
}