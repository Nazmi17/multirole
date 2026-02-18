<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicArticleController extends Controller
{
   public function index(Request $request)
    {
        $query = Article::with(['author', 'categories'])
            ->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // Logic Sorting / Urutan
        $sort = $request->get('sort', 'recent'); // Default 'recent'

        switch ($sort) {
            case 'popular_week':
                // Artikel yang terbit 7 hari terakhir, diurutkan by views
                $query->where('published_at', '>=', now()->subWeek())
                      ->orderByDesc('views_count');
                break;

            case 'popular_month':
                // Artikel yang terbit 30 hari terakhir, diurutkan by views
                $query->where('published_at', '>=', now()->subMonth())
                      ->orderByDesc('views_count');
                break;

            case 'popular_all':
                $query->orderByDesc('views_count');
                break;
            
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;

            case 'recent':
            default:
                $query->latest('published_at');
                break;
        }

        // Eksekusi Pagination (Pertahankan filter saat pindah halaman)
        $articles = $query->paginate(3)->withQueryString();

        $categories = Category::whereHas('articles', function($q) {
            $q->where('status', 'published');
        })->get();

        $authors = User::whereHas('articles', function($q) {
            $q->where('status', 'published');
        })->get();

        return view('pages.articles', compact('articles', 'categories', 'authors'));
    }

    public function show(Article $article)
    {
        // Pastikan hanya artikel published yang bisa dibuka
        if ($article->status !== 'published') {
            abort(404);
        }

        // Increment views count (Statistik sederhana)
        $article->increment('views_count');

        return view('pages.article-detail', compact('article'));
    }

    public function like(Article $article)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Toggle Like (Kalau udah like jadi unlike, kalau belum jadi like)
        $article->likes()->toggle($user->id);

        // Hitung ulang jumlah like dan simpan ke kolom likes_count
        $newCount = $article->likes()->count();
        $article->update(['likes_count' => $newCount]);

        // Cek status sekarang (apakah jadi di-like atau di-unlike)
        $isLiked = $article->isLikedBy($user);

        return response()->json([
            'likes_count' => $newCount,
            'is_liked' => $isLiked
        ]);
    }

    public function storeComment(Request $request, Article $article)
    {
        // 1. Validasi Input
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // 2. Simpan Komentar
        $article->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function destroyComment(Comment $comment)
    {
        // Pastikan yang menghapus adalah pemilik komentar
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar dihapus.');
    }

    public function author(Request $request, User $user)
    {
        // 1. Hitung Statistik Penulis
        // Kita gunakan query builder 'where status published' agar draft tidak terhitung
        $stats = [
            'total_articles' => $user->articles()->where('status', 'published')->count(),
            'total_views' => $user->articles()->where('status', 'published')->sum('views_count'),
            'total_likes' => $user->articles()->where('status', 'published')->sum('likes_count'),
            'join_date' => $user->created_at->format('F Y'),
        ];

        // 2. Query Artikel (Milik User Ini + Published)
        $query = $user->articles()->with('categories')->where('status', 'published');

        // --- LOGIC FILTER (Sama seperti Index, tapi tanpa filter Author) ---
        
        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filter Category
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'popular_all':
                $query->orderByDesc('views_count'); break;
            case 'oldest':
                $query->orderBy('published_at', 'asc'); break;
            case 'recent':
            default:
                $query->latest('published_at'); break;
        }

        $articles = $query->paginate(9)->withQueryString();

        // 3. Ambil Kategori (Hanya yang pernah dipakai oleh penulis ini)
        // Agar dropdown tidak penuh dengan kategori yang tidak relevan dengan penulis ini
        $categories = Category::whereHas('articles', function($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'published');
        })->get();

        return view('pages.author', compact('user', 'articles', 'stats', 'categories'));
    }
}