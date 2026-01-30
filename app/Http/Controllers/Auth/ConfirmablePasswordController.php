<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class ConfirmablePasswordController extends Controller
{
    /**
     * Tampilkan view konfirmasi.
     */
    public function show(): View
    {
        return view('auth.confirm-password', [
            // Kirim status apakah user punya 2FA aktif
            'userHas2FA' => !empty(Auth::user()->google2fa_secret)
        ]);
    }

    /**
     * Proses konfirmasi (Bisa Password ATAU OTP).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. Cek apakah user input OTP (dan punya secret)
        if ($request->filled('otp') && $user->google2fa_secret) {
            
            $valid = Google2FA::verifyKey($user->google2fa_secret, $request->otp);

            if ($valid) {
                $request->session()->put('auth.password_confirmed_at', time());
                return redirect()->intended(route('dashboard', absolute: false));
            }

            throw ValidationException::withMessages([
                'otp' => __('Kode OTP salah.'),
            ]);
        }

        // 2. Jika tidak pakai OTP, Validasi Password biasa (Fallback)
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}