@extends('layouts-pages.app')

@section('content')
    {{-- CSS TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control { border-radius: 0.75rem; padding: 0.75rem 1rem; border-color: #e5e7eb; }
        .ts-control:focus { border-color: #6366f1; box-shadow: none; }
        .ts-dropdown { border-radius: 0.75rem; overflow: hidden; z-index: 50; }
    </style>

    <div class="min-h-screen bg-background pb-20">
        
        <div class="bg-primary pt-32 pb-12 md:pt-40 md:pb-16 rounded-b-[3rem] shadow-sm mb-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                    
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" 
                                class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-4xl font-bold text-secondary">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    {{-- Info User --}}
                    <div class="text-center md:text-left flex-1">
                        <h1 class="font-heading text-4xl md:text-5xl text-foreground mb-2">{{ $user->name }}</h1>
                        <p class="text-foreground/70 font-paragraph text-lg mb-6">
                            Member since {{ $stats['join_date'] }}
                        </p>

                        {{-- Kartu Statistik --}}
                        <div class="grid grid-cols-3 gap-4 md:gap-8 max-w-lg mx-auto md:mx-0">
                            {{-- Total Articles --}}
                            <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/40">
                                <div class="text-2xl md:text-3xl font-bold text-secondary">{{ $stats['total_articles'] }}</div>
                                <div class="text-xs md:text-sm text-foreground/70 font-bold uppercase tracking-wide">Articles</div>
                            </div>
                            {{-- Total Views --}}
                            <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/40">
                                <div class="text-2xl md:text-3xl font-bold text-secondary">{{ number_format($stats['total_views']) }}</div>
                                <div class="text-xs md:text-sm text-foreground/70 font-bold uppercase tracking-wide">Reads</div>
                            </div>
                            {{-- Total Likes --}}
                            <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/40">
                                <div class="text-2xl md:text-3xl font-bold text-secondary">{{ number_format($stats['total_likes']) }}</div>
                                <div class="text-xs md:text-sm text-foreground/70 font-bold uppercase tracking-wide">Likes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[100rem] mx-auto px-6">
            
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-10 border border-gray-100">
                <form action="{{ route('public.author', $user) }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        {{-- Search --}}
                        <div class="md:col-span-5">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Search {{ $user->name }}'s articles</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Keyword..."
                                    class="w-full rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 pl-10">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="md:col-span-4">
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

                        {{-- Sort --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">Sort By</label>
                            <select name="sort" class="w-full rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 bg-white cursor-pointer" onchange="this.form.submit()">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="popular_all" {{ request('sort') == 'popular_all' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="md:col-span-1">
                            <button type="submit" class="w-full bg-secondary text-secondary-foreground font-bold py-3 rounded-xl hover:opacity-90 transition shadow-sm">
                                Go
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if($articles->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($articles as $article)
                         <a href="{{ route('public.articles.show', $article) }}" class="block group">
                            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:scale-105 transition duration-300 h-full flex flex-col">
                                
                                {{-- Image (700x450 Ratio) --}}
                                <div class="relative aspect-[700/450] overflow-hidden bg-gray-200">
                                    @if($article->featured_image)
                                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    @if($article->categories->isNotEmpty())
                                        <div class="absolute top-4 left-4 bg-secondary text-secondary-foreground font-paragraph font-bold text-sm py-2 px-4 rounded-xl shadow-md">
                                            {{ $article->categories->first()->title }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="p-6 flex-1 flex flex-col">
                                    <h2 class="font-heading text-2xl text-foreground mb-3 line-clamp-2 group-hover:text-secondary transition-colors">
                                        {{ $article->title }}
                                    </h2>
                                    <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-3 leading-relaxed flex-1">
                                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-foreground/60 mt-auto pt-4 border-t border-gray-100">
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
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $articles->links() }} 
                </div>

            @else
                <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-2xl font-heading text-gray-800">No articles found</h3>
                    <p class="text-gray-500 mt-2">{{ $user->name }} hasn't posted anything matching your filters yet.</p>
                    <a href="{{ route('public.author', $user) }}" class="inline-block mt-6 px-6 py-2 bg-secondary text-secondary-foreground rounded-full hover:opacity-90 transition font-bold">
                        View All by {{ $user->name }}
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- Script TomSelect --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#filter-category", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                controlInput: '<input>',
                maxOptions: null
            });
        });
    </script>
@endsection