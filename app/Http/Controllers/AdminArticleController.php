<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class AdminArticleController extends Controller
{
    use AuthorizesRequests;

    // 1. Dashboard Editor: List Artikel Masuk
    public function index()
    {
        Gate::authorize('viewAny', Article::class);

        // Urutkan: Pending paling atas, baru Published/Rejected
        $articles = Article::with(['author', 'categories'])
            ->where('status', '!=', 'draft') // Editor tidak melihat Draft user
            ->orderByRaw("FIELD(status, 'pending', 'published', 'rejected')")
            ->latest()
            ->paginate(15);

        return view('articles.admin.index', compact('articles'));
    }

    // 2. Preview Artikel sebelum Approve
    public function show(Article $article)
    {
        $this->authorize('approve', $article);
        return view('articles.admin.show', compact('article'));
    }

    // 3. Action: Approve (Pending -> Published)
    public function approve(Article $article)
    {
        Gate::authorize('approve', $article);
        $article->update([
            'status' => 'published',
            'published_at' => now(),
            'admin_feedback' => null // Reset feedback jika sebelumnya ada
        ]);

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel '{$article->title}' telah diterbitkan.");
    }

    // 4. Action: Reject (Pending -> Rejected)
    public function reject(Request $request, Article $article)
    {
        Gate::authorize('approve', $article);
        $request->validate([
            'admin_feedback' => 'required|string|min:5',
        ], [
            'admin_feedback.required' => 'Wajib memberikan alasan penolakan agar penulis bisa merevisi.'
        ]);

        $article->update([
            'status' => 'rejected',
            'admin_feedback' => $request->admin_feedback
        ]);

        return redirect()->route('admin.articles.index')
            ->with('warning', "Artikel dikembalikan ke penulis untuk revisi.");
    }
}