<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Facade as Google2FA; // <--- PENTING: Pakai Facade ini

class TwoFactorController extends Controller
{
    // 1. Tampilkan Halaman Setup (QR Code) di Profile
    public function index()
    {
        $user = Auth::user();
        
        // KONDISI 1: User SUDAH punya secret (2FA Aktif)
        if ($user->google2fa_secret) {
            return view('profile.two-factor', [
                'enabled' => true,
                'QR_Image' => null, 
                'secret' => null    
            ]);
        }

        // KONDISI 2: User BELUM punya secret (Generate QR Baru)
        $secretKey = Google2FA::generateSecretKey();

        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secretKey
        );

        return view('profile.two-factor', [
            'enabled' => false,     // <--- PASTEKAN INI ADA DI SINI
            'QR_Image' => $QR_Image,
            'secret' => $secretKey
        ]);
    }

    // 2. Simpan Secret Key (Aktifkan 2FA)
    public function store(Request $request)
    {
        $request->validate([
            'otp' => 'required', 
            'secret' => 'required'
        ]);

        // Validasi kode OTP pakai Facade
        // Note: verifyKey(secret, code)
        $valid = Google2FA::verifyKey($request->secret, $request->otp);

        if ($valid) {
            $user = Auth::user();
            $user->google2fa_secret = $request->secret;
            $user->save();

            // Set session bahwa user ini sudah verified sesi ini
            session(['2fa_verified' => true]);

            return redirect()->route('profile.edit')->with('success', '2FA Berhasil Diaktifkan!');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah. Silakan coba lagi.']);
    }

    // 3. Hapus 2FA (Disable)
    public function destroy()
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->save();
        
        return back()->with('success', '2FA Berhasil Dinonaktifkan.');
    }

    // 4. Halaman input OTP saat Login (Challenge)
    public function challenge()
    {
        return view('auth.two-factor-challenge');
    }

    // 5. Proses Verifikasi OTP saat Login
    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $user = Auth::user();

        // Validasi pakai Facade
        $valid = Google2FA::verifyKey($user->google2fa_secret, $request->otp);

        if ($valid) {
            session(['2fa_verified' => true]);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['otp' => 'Kode OTP salah.']);
    }
}