<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
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
   public function store(Request $request): RedirectResponse | JsonResponse
    {
        $user = $request->user(); // Pakai $request->user() lebih aman

        // Validasi input email_otp agar tidak error saat dibaca
        $request->validate([
            'email_otp' => 'nullable|string',
        ]);

        $isConfirmed = false; // Penanda status

        // === 1. CEK EMAIL OTP (Prioritas user Google) ===
        if ($request->filled('email_otp')) {
            $sessionCode = session('auth_verification_code');
            $expiresAt = session('auth_verification_code_expires_at');

            // Cek kesesuaian kode & waktu kadaluarsa
            if ($sessionCode && $expiresAt && now()->lessThan($expiresAt) && $request->email_otp == $sessionCode) {
                $isConfirmed = true;
                // Hapus kode dari session biar aman (sekali pakai)
                $request->session()->forget(['auth_verification_code', 'auth_verification_code_expires_at']);
            } else {
                throw ValidationException::withMessages([
                    'email_otp' => __('Kode verifikasi email salah atau sudah kadaluarsa.'),
                ]);
            }
        }

        // === 2. CEK GOOGLE AUTHENTICATOR (Jika diisi) ===
        elseif ($request->filled('otp') && $user->google2fa_secret) {
            $valid = Google2FA::verifyKey($user->google2fa_secret, $request->otp);

            if ($valid) {
                $isConfirmed = true;
            } else {
                throw ValidationException::withMessages([
                    'otp' => __('Kode Authenticator salah.'),
                ]);
            }
        }

        // === 3. CEK PASSWORD BIASA (Fallback terakhir) ===
        else {
            // Validasi password bawaan Laravel
            if (! Auth::guard('web')->validate([
                'email' => $user->email,
                'password' => $request->password,
            ])) {
                // Jangan throw error jika input password kosong (karena mungkin user Google salah klik tombol)
                // Tapi jika user MEMANG input password dan salah, baru throw error.
                if ($request->filled('password')) {
                    throw ValidationException::withMessages([
                        'password' => __('auth.password'),
                    ]);
                }
                
                // Jika semua kosong (gak isi password, gak isi OTP), lempar error umum
                throw ValidationException::withMessages([
                    'password' => __('Harap masukkan password atau kode verifikasi.'),
                ]);
            }
            $isConfirmed = true;
        }

        // === FINALISASI ===
        if ($isConfirmed) {
            $request->session()->put('auth.password_confirmed_at', time());

            // Pastikan return JSON untuk Axios
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Password confirmed']);
            }

            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        // Safety net (harusnya tidak pernah sampai sini karena throw di atas)
        return back();
    }
}