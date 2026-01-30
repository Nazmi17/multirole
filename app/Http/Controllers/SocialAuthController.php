<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Cari user berdasarkan ID provider atau Email
            $user = User::where($provider . '_id', $socialUser->getId())
                        ->orWhere('email', $socialUser->getEmail())
                        ->first();

            if (!$user) {
                // --- KASUS: USER BARU ---
                // Kita harus generate username karena kolom ini sekarang wajib/required
                // Contoh hasil: budi-santoso-882
                $generatedUsername = Str::slug($socialUser->getName()) . '-' . rand(100, 999);

                $user = User::create([
                    'name'              => $socialUser->getName(),
                    'username'          => $generatedUsername, // <--- PERBAIKAN 1: Tambah Username
                    'email'             => $socialUser->getEmail(),
                    'password'          => bcrypt(Str::random(16)), // Password acak
                    'email_verified_at' => now(), 
                    $provider . '_id'   => $socialUser->getId(),
                    'avatar'            => $socialUser->getAvatar(),
                    'is_active'         => true,
                    'is_profile_complete' => false,
                ]);

                // Assign Role Default (Pastikan nama role 'staff' atau 'user' ada di database)
                $user->assignRole('user'); 
            } else {
                // --- KASUS: USER LAMA ---
                // Update ID provider jika belum ada
                if (empty($user->{$provider . '_id'})) {
                    $user->update([
                        $provider . '_id' => $socialUser->getId(),
                        'avatar'          => $socialUser->getAvatar(),
                    ]);
                }
                
            }

            if (! $user->is_active) {
                return redirect('/login')->withErrors(['email' => 'Akun Google Anda dinonaktifkan oleh Admin.']);
            }

            Auth::login($user);

            if (!$user->is_profile_complete) {
                return redirect()->route('profile.edit')->with('warning', 'Silakan lengkapi Username dan Password Anda terlebih dahulu.');
            }

            return $this->redirectBasedOnRole($user);

        } catch (\Exception $e) {
            // Debugging: Uncomment baris di bawah untuk melihat error aslinya di layar
            // dd($e->getMessage()); 
            
            return redirect('/login')->with('error', 'Gagal login: ' . $e->getMessage());
        }
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index'); // Sesuaikan route admin kamu
        }
        return redirect()->route('dashboard');
    }
}