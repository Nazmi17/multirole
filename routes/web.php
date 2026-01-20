<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware(['can: Edit profile'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Management User
Route::get('/users', [UserManagementController::class, 'index'])->middleware(['can:view users'])->name('admin.users.index');
Route::get('/users/create', [UserManagementController::class, 'create'])->middleware(['can:create users'])->name('admin.users.create');
Route::post('/users', [UserManagementController::class, 'store'])->middleware(['can:create users'])->name('admin.users.store');
Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->middleware(['can:edit users'])->name('admin.users.edit');
Route::put('/users/{user}', [UserManagementController::class, 'update'])->middleware(['can:edit users'])->name('admin.users.update');
Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->middleware(['can:delete users'])->name('admin.users.destroy');
});

Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// 2. Multi-role Routes
// Dashboard untuk USER BIASA
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Dashboard untuk ADMIN
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Halaman Utama Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Action untuk Tambah Role Baru
    Route::post('/roles', [AdminDashboardController::class, 'storeRole'])->name('roles.store');
    
    // Action untuk Update Permission pada Role (Centang Checkbox)
    Route::put('/roles/{role}/permissions', [AdminDashboardController::class, 'updateRolePermissions'])->name('roles.permissions.update');
    
    // Action untuk Hapus Role
    Route::delete('/roles/{role}', [AdminDashboardController::class, 'destroyRole'])->name('roles.destroy');
});

require __DIR__.'/auth.php';
