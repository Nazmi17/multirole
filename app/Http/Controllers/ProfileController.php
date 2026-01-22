<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. ISI DULU DATA STANDAR (Nama, Email, dll)
        // Pindahkan ini ke paling atas supaya tidak menimpa logic avatar nanti
        $user->fill($request->validated());

        // 2. LOGIKA PROFILE COMPLETE (Username & Password)
        if (!$user->is_profile_complete) {
            // Validasi tambahan manual jika belum complete
            $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);
            
            // Set data manual
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->is_profile_complete = true;
        } else {
            // Jika user sudah complete profile tapi mau ganti password (optional check)
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
        }

        // 3. LOGIKA AVATAR (Local Storage)
        // Ditaruh di bawah 'fill' agar menimpa data avatar mentah dengan path yang benar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan file-nya eksis
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan file baru
            // Hasil: "avatar/namafileacak.jpg"
            $path = $request->file('avatar')->store('avatar', 'public');

            // Set path ke database (Ini akan menjadi nilai final)
            $user->avatar = $path;
        }

        // 4. Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}