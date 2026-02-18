@extends('layouts-pages.app')

{{-- Bagian Meta SEO untuk Social Media Share --}}
@section('meta')
    <title>{{ $article->meta_title ?? $article->title }} - Nazmi Restaurant</title>
    <meta name="description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->content), 150) }}">
    <meta name="keywords" content="{{ $article->meta_keywords }}">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}" />
    <meta property="og:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->content), 150) }}" />
    <meta property="og:image" content="{{ $article->featured_image ? Storage::url($article->featured_image) : asset('images/default-article.jpg') }}" />
    <meta property="og:url" content="{{ route('public.articles.show', $article) }}" />
@endsection

@section('content')
    <div class="min-h-screen bg-white">
        
        {{-- 1. HERO SECTION & HEADER --}}
        <div class="w-full bg-primary/10 pt-32 pb-10 md:pt-40 md:pb-16">
            <div class="max-w-4xl mx-auto px-6 text-center">
                
                {{-- Kategori Badge --}}
                @if($article->categories->isNotEmpty())
                    <div class="flex justify-center gap-2 mb-4">
                        @foreach($article->categories as $category)
                            <a href="{{ route('public.articles.index', ['category' => $category->slug]) }}" 
                               class="bg-secondary text-secondary-foreground text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider hover:opacity-80 transition">
                                {{ $category->title }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Judul Artikel --}}
                <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl text-foreground mb-6 leading-tight">
                    {{ $article->title }}
                </h1>

                {{-- Meta Info (Author, Date, Read Time, Views) --}}
                <div class="flex flex-wrap justify-center items-center gap-4 text-sm md:text-base text-foreground/70 font-paragraph">
                    <div class="flex items-center gap-2">
                        @if($article->author->avatar)
                            <img src="{{ Storage::url($article->author->avatar) }}" alt="{{ $article->author->name }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($article->author->name, 0, 2) }}
                            </div>
                        @endif
                        <a href="{{ route('public.author', $article->author) }}" class="font-paragraph font-medium hover:text-secondary hover:underline transition">
                            {{ $article->author->name }}
                        </a>
                    </div>
                    <span>•</span>
                    <div class="flex items-center gap-1">
                        <span>📅</span>
                        <span>{{ $article->published_at->format('d M Y') }}</span>
                    </div>
                    <span>•</span>
                    <div class="flex items-center gap-1">
                        <span>⏱️</span>
                        <span>{{ $article->read_time }}</span>
                    </div>
                    <span>•</span>
                    <div class="flex items-center gap-1" title="Total Views">
                        <span>👁️</span>
                        <span>{{ number_format($article->views_count) }} Views</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. FEATURED IMAGE --}}
        @if($article->featured_image)
            <div class="max-w-5xl mx-auto px-6 -mt-8 mb-12">
                <div class="rounded-2xl overflow-hidden shadow-2xl aspect-[700/450] max-h-[600px] bg-gray-100">
                    <img src="{{ Storage::url($article->featured_image) }}" 
                            alt="{{ $article->title }}" 
                            class="w-full h-full object-cover">
                </div>
            </div>
        @endif

        {{-- 3. CONTENT AREA --}}
        <div class="max-w-3xl mx-auto px-6 pb-20">
            
            {{-- Artikel Content (Prose Tailwind) --}}
            <article class="prose prose-lg md:prose-xl prose-headings:font-heading prose-p:font-paragraph prose-a:text-secondary hover:prose-a:text-secondary/80 max-w-none text-foreground/90 leading-relaxed">
                {!! $article->content !!}
            </article>

            {{-- Divider --}}
            <div class="border-t border-gray-200 my-12"></div>

            {{-- 4. INTERACTION SECTION (Like & Share) --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                
                {{-- Tombol Like --}}
                <div class="flex items-center gap-4">
                    @auth
                        <button id="like-btn" data-id="{{ $article->id }}" 
                            class="flex items-center gap-3 px-6 py-3 rounded-full border-2 transition-all duration-300 group
                            {{ $article->isLikedBy(auth()->user()) ? 'bg-pink-50 border-pink-200 text-pink-600' : 'bg-white border-gray-200 text-gray-600 hover:border-pink-200 hover:text-pink-500' }}">
                            
                            <svg id="like-icon" class="w-7 h-7 transition-transform group-hover:scale-110 {{ $article->isLikedBy(auth()->user()) ? 'fill-current' : 'fill-none' }}" 
                                 stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            
                            <div class="flex flex-col text-left leading-none">
                                <span class="text-xs font-bold uppercase tracking-wide">Like this story</span>
                                <span class="text-lg font-bold" id="like-count">{{ $article->likes_count }}</span>
                            </div>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-6 py-3 rounded-full border-2 bg-white border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            <div class="flex flex-col text-left leading-none">
                                <span class="text-xs font-bold uppercase tracking-wide">Likes</span>
                                <span class="text-lg font-bold">{{ $article->likes_count }}</span>
                            </div>
                        </a>
                    @endauth
                </div>

                {{-- Tombol Share (Opsional) --}}
                <div class="flex gap-2">
                    <span class="text-sm font-bold text-gray-500 self-center mr-2">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition">
                        <span class="font-bold">f</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $article->title }}" target="_blank" class="w-10 h-10 rounded-full bg-sky-400 text-white flex items-center justify-center hover:bg-sky-500 transition">
                        <span class="font-bold">t</span>
                    </a>
                    <a href="https://wa.me/?text={{ $article->title }}%20{{ url()->current() }}" target="_blank" class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition">
                        <span class="font-bold">WA</span>
                    </a>
                </div>
            </div>

           {{-- 5. COMMENTS SECTION --}}
            <div class="mt-16 bg-gray-50 rounded-2xl p-8" id="comments-section">
                <h3 class="font-heading text-2xl mb-6">Comments ({{ $article->comments->count() }})</h3>
                
                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Input Komentar --}}
                @auth
                    <form action="{{ route('articles.comment.store', $article) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="mb-4">
                            <textarea name="body" rows="3" required
                                class="w-full rounded-xl border-gray-300 focus:border-secondary focus:ring-secondary placeholder-gray-400" 
                                placeholder="What are your thoughts?"></textarea>
                            @error('body')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-secondary text-secondary-foreground font-bold py-2 px-6 rounded-xl hover:opacity-90 transition">
                                Post Comment
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-white p-6 rounded-xl text-center border border-gray-200 mb-8">
                        <p class="text-gray-600 mb-4">Please login to join the discussion.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-primary text-foreground font-bold py-2 px-6 rounded-xl hover:bg-primary/80 transition">
                            Log In
                        </a>
                    </div>
                @endauth

                {{-- List Komentar --}}
                <div class="space-y-6">
                    @forelse($article->comments as $comment)
                        <div class="flex gap-4 group">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0">
                                @if($comment->user->avatar)
                                    <img src="{{ Storage::url($comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Isi Komentar --}}
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    {{-- Tombol Hapus (Hanya muncul jika ini komentar milik user yang sedang login) --}}
                                    @if(Auth::id() === $comment->user_id)
                                        <form action="{{ route('articles.comment.destroy', $comment) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline" onclick="return confirm('Delete this comment?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">
                                    {{ $comment->body }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500 italic">No comments yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>
            </div>

    {{-- Script AJAX Like --}}
    @auth
    <script>
        document.getElementById('like-btn').addEventListener('click', function() {
            const btn = this;
            const icon = document.getElementById('like-icon');
            const countSpan = document.getElementById('like-count');
            const articleId = btn.getAttribute('data-id');

            // Tambahkan efek klik sementara
            btn.classList.add('scale-95');
            setTimeout(() => btn.classList.remove('scale-95'), 150);

            fetch(`/articles/${articleId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
            })
            .then(response => {
                if(response.status === 401) window.location.href = "{{ route('login') }}";
                return response.json();
            })
            .then(data => {
                countSpan.innerText = data.likes_count;

                if (data.is_liked) {
                    btn.classList.remove('bg-white', 'border-gray-200', 'text-gray-600');
                    btn.classList.add('bg-pink-50', 'border-pink-200', 'text-pink-600');
                    icon.classList.remove('fill-none');
                    icon.classList.add('fill-current');
                } else {
                    btn.classList.add('bg-white', 'border-gray-200', 'text-gray-600');
                    btn.classList.remove('bg-pink-50', 'border-pink-200', 'text-pink-600');
                    icon.classList.add('fill-none');
                    icon.classList.remove('fill-current');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    @endauth
@endsection