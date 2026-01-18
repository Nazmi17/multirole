<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    // 1. Redirect ke Provider (Google/FB)
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. Callback dari Provider
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Cari user berdasarkan ID provider atau Email
            $user = User::where($provider . '_id', $socialUser->getId())
                        ->orWhere('email', $socialUser->getEmail())
                        ->first();

            if (!$user) {
                // Jika user belum ada, buat baru
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // Password dummy acak
                    'role' => 'user', // Default role
                    $provider . '_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            } else {
                // Jika user ada tapi belum link ID provider, update datanya
                if (empty($user->{$provider . '_id'})) {
                    $user->update([
                        $provider . '_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(), // Update avatar jika perlu
                    ]);
                }
            }

            // Login user
            Auth::login($user);

            // Redirect sesuai role
            return $this->redirectBasedOnRole($user);

        } catch (\Exception $e) {
            dd($e->getMessage());
            // return redirect('/login')->with('error', 'Gagal login dengan ' . $provider);
        }
    }

    // Helper redirect role
    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('dashboard'));
    }
}