<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;       // <--- Wajib ada
use Illuminate\Support\Facades\Mail; // <--- Wajib ada
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', '2fa'])->group(function () {
    Route::middleware(['can:Edit profile'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware(['password.confirm'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware(['password.confirm'])
        ->name('profile.destroy');
    });
});
Route::middleware(['auth', 'verified', '2fa'])->group(function () {
     Route::get('/profile/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/profile/2fa', [TwoFactorController::class, 'store'])->name('2fa.store');
    Route::delete('/profile/2fa', [TwoFactorController::class, 'destroy'])->name('2fa.destroy');
    
    // --- 2FA CHALLENGE (LOGIN) ---
    Route::get('/2fa-challenge', [TwoFactorController::class, 'challenge'])->name('2fa.challenge');
    Route::post('/2fa-challenge', [TwoFactorController::class, 'verify'])->name('2fa.verify');

    // Management User
    Route::get('/users', [UserManagementController::class, 'index'])->middleware(['can:view users'])->name('admin.users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->middleware(['can:create users'])->name('admin.users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->middleware(['can:create users'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->middleware(['can:edit users'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->middleware(['can:edit users'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->middleware(['can:delete users'])->name('admin.users.destroy');
    Route::get('/users/trash', [UserManagementController::class, 'trash'])->name('admin.users.trash');
    Route::put('/users/{id}/restore', [UserManagementController::class, 'restore'])->name('admin.users.restore');
    Route::delete('/users/{id}/force-delete', [UserManagementController::class, 'forceDelete'])->name('admin.users.force_delete');

});
Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Dashboard untuk USER BIASA
Route::middleware(['auth', 'verified', '2fa'])->group(function () {
     Route::get('/dashboard', function () {
         return view('dashboard');
     })->name('dashboard');
      });

// Dashboard untuk ADMIN
Route::middleware(['auth', 'verified', '2fa', 'role:admin'])->prefix('admin')->group(function () {
    // Halaman Utama Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Action untuk Tambah Role Baru
    Route::post('/roles', [AdminDashboardController::class, 'storeRole'])->name('admin.roles.store');
    
    // Action untuk Update Permission pada Role (Centang Checkbox)
    Route::put('/roles/{role}/permissions', [AdminDashboardController::class, 'updateRolePermissions'])->name('admin.roles.permissions.update');
    
    // Action untuk Hapus Role
    Route::delete('/roles/{role}', [AdminDashboardController::class, 'destroyRole'])->name('admin.roles.destroy');
});

Route::get('/auth/status', function () {
    $timeout = config('auth.password_timeout', 10800);
    $confirmedAt = session('auth.password_confirmed_at', 0);
    
    return response()->json([
        'confirmed' => (time() - $confirmedAt) < $timeout
    ]);
})->middleware('auth');

// Route untuk mengirim kode OTP ke email
Route::post('/auth/send-email-otp', function (Request $request) {
    $user = $request->user();
    
    // Generate 6 angka acak
    $code = rand(100000, 999999);
    
    // Simpan di session selama 5 menit
    Session::put('auth_verification_code', $code);
    Session::put('auth_verification_code_expires_at', now()->addMinutes(5));
    
    // Kirim Email (Simple)
    Mail::raw("Kode verifikasi keamanan Anda adalah: $code. Kode ini berlaku selama 5 menit.", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Kode Verifikasi Keamanan');
    });
    
    return response()->json(['message' => 'Kode terkirim ke email Anda.']);
})->middleware('auth');

require __DIR__.'/auth.php';
