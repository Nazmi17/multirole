@extends('layouts-pages.app')

@section('content')
    {{-- CSS TomSelect (Wajib ada) --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Custom Style untuk TomSelect agar match dengan input Tailwind */
        .ts-control { border-radius: 0.75rem; padding: 0.75rem 1rem; border-color: #e5e7eb; }
        .ts-control:focus { border-color: #6366f1; box-shadow: none; }
        .ts-dropdown { border-radius: 0.75rem; overflow: hidden; }
    </style>

    <div class="min-h-screen bg-background">
        
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Articles & Stories
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Discover the rich heritage of Sundanese cuisine through our collection of stories, recipes, and cultural insights.
                </p>
            </div>
        </section>

        <section class="w-full py-8 bg-white shadow-sm sticky top-[80px] z-30 transition-all duration-300" id="filter-bar">
            <div class="max-w-[100rem] mx-auto px-6">
                
                <form action="{{ route('public.articles.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        {{-- 1. Search Bar (Lebar) --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Search Keyword</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Find recipe, culture, or story..."
                                    class="w-full rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 pl-10">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                            </div>
                        </div>

                        {{-- 2. Filter Kategori (TomSelect) --}}
                        <div class="md:col-span-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Category</label>
                            <select id="filter-category" name="category" placeholder="All Categories" autocomplete="off">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Filter Penulis (TomSelect) --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Author</label>
                            <select id="filter-author" name="author" placeholder="All Authors" autocomplete="off">
                                <option value="">All Authors</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 4. Sorting --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Sort By</label>
                            <select name="sort" class="w-full rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 bg-white cursor-pointer" onchange="this.form.submit()">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="popular_week" {{ request('sort') == 'popular_week' ? 'selected' : '' }}>🔥 Popular This Week</option>
                                <option value="popular_month" {{ request('sort') == 'popular_month' ? 'selected' : '' }}>🌟 Popular This Month</option>
                                <option value="popular_all" {{ request('sort') == 'popular_all' ? 'selected' : '' }}>🏆 All Time Popular</option>
                            </select>
                        </div>

                        {{-- 5. Submit & Reset --}}
                        <div class="md:col-span-1 flex gap-2">
                            <button type="submit" class="w-full bg-secondary text-secondary-foreground font-bold py-3 rounded-xl hover:opacity-90 transition shadow-sm">
                                Go
                            </button>
                            @if(request()->hasAny(['search', 'category', 'author', 'sort']))
                                <a href="{{ route('public.articles.index') }}" class="w-full bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition text-center flex items-center justify-center" title="Reset Filters">
                                    ↺
                                </a>
                            @endif
                        </div>

                    </div>
                </form>

                {{-- Indikator Hasil Pencarian --}}
                @if(request()->hasAny(['search', 'category', 'author', 'sort']))
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                        <span>Showing results for:</span>
                        @if(request('search')) <span class="bg-gray-100 px-2 py-1 rounded-md font-bold text-gray-700">"{{ request('search') }}"</span> @endif
                        @if(request('category')) 
                            @php $catName = $categories->find(request('category'))?->title; @endphp
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md font-bold">Category: {{ $catName }}</span> 
                        @endif
                        @if(request('author')) 
                            @php $authName = $authors->find(request('author'))?->name; @endphp
                            <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-md font-bold">Author: {{ $authName }}</span> 
                        @endif
                        @if(request('sort') && request('sort') != 'recent') 
                            <span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded-md font-bold">Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}</span> 
                        @endif
                    </div>
                @endif
            </div>
        </section>

        <section class="w-full py-16 md:py-24 min-h-[600px]">
            <div class="max-w-[100rem] mx-auto px-6">
                
                @if($articles->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($articles as $article)
                            {{-- ... (CODE CARD ARTIKEL SAMA SEPERTI SEBELUMNYA) ... --}}
                            {{-- Copy paste isi foreach dari kode sebelumnya --}}
                             {{-- Hapus tag <a> pembungkus utama, pindahkan class 'group' ke article --}}
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 h-full flex flex-col group">
                                
                                {{-- 1. IMAGE WRAPPER (Link Artikel ditaruh disini) --}}
                                <div class="relative aspect-[700/450] overflow-hidden bg-gray-200">
                                    <a href="{{ route('public.articles.show', $article) }}" class="block w-full h-full">
                                        @if($article->featured_image)
                                            <img
                                                src="{{ Storage::url($article->featured_image) }}"
                                                alt="{{ $article->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy"
                                            />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Category Badge --}}
                                    @if($article->categories->isNotEmpty())
                                        <div class="absolute top-4 left-4 pointer-events-none"> {{-- pointer-events-none agar klik tembus ke gambar --}}
                                            <span class="bg-secondary text-secondary-foreground font-paragraph font-bold text-sm py-2 px-4 rounded-xl shadow-md">
                                                {{ $article->categories->first()->title }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- 2. CONTENT AREA --}}
                                <div class="p-6 flex-1 flex flex-col">
                                    
                                    {{-- Link Artikel ditaruh di JUDUL juga --}}
                                    <a href="{{ route('public.articles.show', $article) }}" class="block">
                                        <h2 class="font-heading text-2xl text-foreground mb-3 line-clamp-2 group-hover:text-secondary transition-colors">
                                            {{ $article->title }}
                                        </h2>
                                    </a>
                                    
                                    <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-3 leading-relaxed flex-1">
                                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                                    </p>

                                    {{-- Meta Info --}}
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-foreground/60 mt-auto pt-4 border-t border-gray-100">
                                        
                                        {{-- Link Author (Aman disini karena tidak ada parent <a>) --}}
                                        <div class="flex items-center gap-2">
                                            <span class="w-4 text-center">👤</span>
                                            <a href="{{ route('public.author', $article->author) }}" class="font-paragraph font-medium hover:text-secondary hover:underline transition relative z-10">
                                                {{ $article->author->name }}
                                            </a>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span class="w-4 text-center">👁️</span>
                                            <span class="font-paragraph">{{ number_format($article->views_count) }}</span>
                                        </div>

                                        <div class="flex items-center gap-2 ml-auto">
                                            <span class="w-4 text-center">📅</span>
                                            <span class="font-paragraph">{{ $article->published_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $articles->links() }} 
                    </div>

                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-heading text-gray-800">No articles found</h3>
                        <p class="text-gray-500 mt-2">We couldn't find anything matching your filters.</p>
                        <a href="{{ route('public.articles.index') }}" class="inline-block mt-6 px-6 py-2 bg-secondary text-secondary-foreground rounded-full hover:opacity-90 transition font-bold">
                            Clear Filters & View All
                        </a>
                    </div>
                @endif
                
            </div>
        </section>
    </div>

    {{-- Script TomSelect (WAJIB ADA di bawah) --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Init Kategori (Searchable)
            new TomSelect("#filter-category", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                controlInput: '<input>', // Agar bisa diketik
                maxOptions: null
            });

            // Init Penulis (Searchable)
            new TomSelect("#filter-author", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                controlInput: '<input>',
                maxOptions: null
            });
        });
    </script>
@endsection