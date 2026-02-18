<?php

use App\Http\Controllers\Admin\EbookController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\PublicEbookController;
use App\Http\Controllers\PublicGalleryAlbumController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserManagementController;
use App\Mail\SecurityCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// --- PUBLIC ROUTES ---
Route::view('/', 'pages.home')->name('home');
Route::view('/catalog', 'pages.catalog')->name('catalog');
// Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/map', 'pages.map')->name('map');

// Saya ganti nama route ini jadi 'team' agar tidak bentrok dengan user profile
Route::view('/team', 'pages.profile')->name('team'); 

Route::get('/gallery', [PublicGalleryAlbumController::class, 'gallery'])->name('public.gallery.index');
Route::get('/gallery/{album:slug}', [PublicGalleryAlbumController::class, 'showAlbum'])->name('public.gallery.show');

// Artikel Publik
Route::get('/articles', [PublicArticleController::class, 'index'])->name('public.articles.index');
Route::get('/articles/{article:slug}', [PublicArticleController::class, 'show'])->name('public.articles.show');
Route::get('/author/{user}', [PublicArticleController::class, 'author'])->name('public.author');

Route::get('/ebooks-page', [PublicEbookController::class, 'index'])->name('public.ebooks.index');
Route::get('/ebooks/download/{ebook}', [PublicEbookController::class, 'download'])->name('ebooks.download');
Route::get('/ebooks/read/{ebook}', [PublicEbookController::class, 'stream'])->name('ebooks.stream');

// --- AUTH & DASHBOARD ROUTES ---
Route::middleware(['auth', 'verified', '2fa'])->group(function () {

    Route::post('/articles/{article}/like', [PublicArticleController::class, 'like'])->name('articles.like');
    
    // Dashboard User Biasa
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Dashboard ADMIN (Prefix admin)
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/roles', [AdminDashboardController::class, 'storeRole'])->name('admin.roles.store');
        Route::put('/roles/{role}/permissions', [AdminDashboardController::class, 'updateRolePermissions'])->name('admin.roles.permissions.update');
        Route::delete('/roles/{role}', [AdminDashboardController::class, 'destroyRole'])->name('admin.roles.destroy');
        
        // User Management
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

    // --- FITUR UMUM USER (CRUD) ---
    Route::resource('categories', CategoryController::class);
    Route::resource('albums', AlbumController::class);
    Route::resource('galleries', GalleryController::class);

    Route::delete('/albums/{album}/gallery/{gallery}', [AlbumController::class, 'removeGallery'])->name('albums.remove_gallery');

    // --- 2FA SETTINGS ---
    Route::get('/profile/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/profile/2fa', [TwoFactorController::class, 'store'])->name('2fa.store');
    Route::delete('/profile/2fa', [TwoFactorController::class, 'destroy'])->name('2fa.destroy');

    Route::get('/trash', [ArticleController::class, 'trash'])->name('articles.trash');
    Route::put('/{id}/restore', [ArticleController::class, 'restore'])->name('articles.restore');
    Route::delete('/{id}/force-delete', [ArticleController::class, 'forceDelete'])->name('articles.force_delete');
    // --- ARTIKEL USER (Penulis) ---
    Route::prefix('my-articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::patch('/{article}/submit', [ArticleController::class, 'submit'])->name('submit');
        Route::post('/upload-image', [ArticleController::class, 'uploadContentImage'])->name('upload_image');
    });

    // --- ARTIKEL EDITOR (Reviewer) ---
    Route::prefix('admin/review-articles')->name('admin.articles.')->middleware(['role:editor|admin'])->group(function () {
        Route::get('/', [AdminArticleController::class, 'index'])->name('index');
        Route::get('/{article}', [AdminArticleController::class, 'show'])->name('show');
        Route::patch('/{article}/approve', [AdminArticleController::class, 'approve'])->name('approve');
        Route::patch('/{article}/reject', [AdminArticleController::class, 'reject'])->name('reject');
    });

    Route::post('/articles/{article}/comment', [PublicArticleController::class, 'storeComment'])->name('articles.comment.store');
    Route::delete('/comments/{comment}', [PublicArticleController::class, 'destroyComment'])->name('articles.comment.destroy');

    Route::resource('ebooks', EbookController::class);
        Route::get('ebooks/{ebook}/history', [EbookController::class, 'history'])->name('ebooks.history');
});

// --- PROFILE EDIT (BREEZE) ---
// Dipisah dari grup utama agar middleware-nya sesuai logic Breeze/Jetstream default
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- 2FA CHALLENGE & AUTH UTILITIES ---
Route::get('/2fa-challenge', [TwoFactorController::class, 'challenge'])->middleware('auth')->name('2fa.challenge');
Route::post('/2fa-challenge', [TwoFactorController::class, 'verify'])->middleware('auth')->name('2fa.verify');

Route::get('/auth/status', function () {
    $timeout = config('auth.password_timeout', 10800);
    $confirmedAt = session('auth.password_confirmed_at', 0);
    return response()->json(['confirmed' => (time() - $confirmedAt) < $timeout]);
})->middleware('auth');

Route::post('/auth/send-email-otp', function (Request $request) {
    $user = $request->user();
    $code = rand(100000, 999999);
    Session::put('auth_verification_code', $code);
    Session::put('auth_verification_code_expires_at', now()->addMinutes(5));
    Mail::to($user)->send(new SecurityCodeMail($code));
    return response()->json(['message' => 'Kode terkirim ke email Anda.']);
})->middleware('auth');

// Social Auth
Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

require __DIR__.'/auth.php';    