<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Cek apakah slug sudah ada di database?
        // Jika ada, tambahkan angka -1, -2, dst sampai nemu yang kosong.
        // Parameter $ignoreId dipakai saat Edit (biar gak bentrok sama diri sendiri)
        while (Article::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function index() {
        $articles = Article::where('user_id', Auth::id())->with('categories')->latest()->paginate(5);
        return view('articles.user.index', compact('articles'));
    }

    public function create() {
        Gate::authorize('create', Article::class);

        $categories = Category::all();
        return view('articles.user.create', compact('categories'));
    }

    public function store(Request $request) {
        Gate::authorize('create', Article::class);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'excerpt' => ['nullable', 'string', 'max:355'],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500', // Description biasanya agak panjang
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'content', 'excerpt', 'meta_title', 'meta_description', 'meta_keywords']);
        $data['user_id'] = Auth::id();
        $data['slug'] = $this->generateUniqueSlug($request->title);
        $data['status'] = 'draft';

       $data['excerpt'] = $request->input('excerpt') 
        ?? Str::limit(strip_tags($request->input('content')), 150);

        if($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('articles', 'public');
            $data['featured_image'] = $imagePath;
        }

        $article = Article::create($data);

        $article->categories()->sync($request->category_ids);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat!');
    }

    public function edit(Article $article) {
        Gate::authorize('update', $article);
        $categories = Category::all();
        return view('articles.user.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article) {
        Gate::authorize('update', $article);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'category_ids' => ['required', 'array'],
            // 'category_ids.*' => ['exists:categories,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500', // Description biasanya agak panjang
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'content', 'meta_title', 'meta_description', 'meta_keywords']);
        if ($article->title !== $request->title) {
            $data['slug'] = $this->generateUniqueSlug($request->title, $article->id);
        }

        if($request->hasFile('featured_image')) {
            if($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('articles', 'public');
            $data['featured_image'] = $imagePath;
        }

        $article->update($data);
        $article->categories()->sync($request->category_ids);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil diupdate!');
    }

    public function destroy(Article $article) {
        Gate::authorize('delete', $article);
        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
    }

    public function submit(Article $article) {
        Gate::authorize('update', $article);

        if(!$article->status == 'draft') {
            return back()->with('error', 'Artikel sudah dipublikasikan!');
        }
        
        $article->update(['status' => 'pending']);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dipublikasikan!');
    }

    public function uploadContentImage(Request $request)
{
    // Validasi sederhana
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        
        // Simpan file
        $path = $file->store('articles/content', 'public');
        $url = asset('storage/' . $path); // Gunakan asset() agar URL lengkap (http://...)

        // FORMAT RESPON WAJIB UNTUK CKEDITOR 5
        return response()->json([
            'uploaded' => 1, 
            'fileName' => $file->getClientOriginalName(),
            'url' => $url
        ]);
    }

    return response()->json([
        'uploaded' => 0, 
        'error' => ['message' => 'File tidak terkirim.']
    ]);
}
}
