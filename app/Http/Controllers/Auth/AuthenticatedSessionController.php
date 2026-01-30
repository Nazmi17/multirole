<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (! $request->user()->is_active) {
            Auth::guard('web')->logout(); // Tendang keluar
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Anda dinonaktifkan. Hubungi Administrator.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

// admin teu bisa edit admin lain (beres)
// password tambahan validasi frontend (baris)
// login pake username
// nambah foto/avatar
// role langsung kepilih (beres)
// kalau login pake googlw, setelah login langsung ke edit profile edit-edit name dan username dan password, si user ga bisa ke halaman lain sebelum nyelesain ini

// saat ada user terdaftar, notifikasi email selamat ke user dan pemberitahuan ke admin
// ada online/offline di dashboard (beres)
// google capcha di login
// google authentificator tambahin
// tambahan verifikasi password di profile (beres)
// search (beres)
// soft delete (beres)
// saat delete harus verifikasi (nomor/email) (prioritas tarakhir)

// restack editor buat artikel

//username ga boleh ada spasi (beres)
//saat ganti username password email, harus ada verifikasi email
//tambah kolom alamat dan no hp
//admin lain tidak boleh edit admin lain (beres)