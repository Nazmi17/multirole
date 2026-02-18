@extends('layouts-pages.app')

@section('content')
    <div class="min-h-screen bg-background">
        
        {{-- HERO SECTION --}}
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Our Gallery
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Explore our captured moments and activities through these albums.
                </p>
            </div>
        </section>

        {{-- FILTER BAR (Sticky) --}}
        <section class="w-full py-8 bg-white shadow-sm sticky top-[80px] z-30 transition-all duration-300">
            <div class="max-w-[100rem] mx-auto px-6">
                <form action="{{ route('public.gallery.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-end md:items-center">
                        
                        {{-- Search Bar --}}
                        <div class="w-full md:w-1/2 lg:w-1/3 relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Search albums..."
                                class="w-full rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 pl-10">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                        </div>

                        {{-- Sort & Button --}}
                        <div class="flex gap-2 w-full md:w-auto">
                            <select name="sort" class="rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary py-3 px-4 bg-white cursor-pointer" onchange="this.form.submit()">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Photos</option>
                            </select>

                            @if(request()->hasAny(['search', 'sort']))
                                <a href="{{ route('public.gallery.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition text-center flex items-center justify-center" title="Reset Filters">
                                    ↺
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- ALBUM GRID --}}
        <section class="w-full py-16 md:py-24 min-h-[600px]">
            <div class="max-w-[100rem] mx-auto px-6">
                
                @if($albums->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($albums as $album)
                            <a href="{{ route('public.gallery.show', $album) }}" class="group block h-full">
                                <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 h-full flex flex-col">
                                    
                                    {{-- Image Wrapper --}}
                                    <div class="relative aspect-[4/3] overflow-hidden bg-gray-200">
                                        @if($album->cover_image)
                                            <img
                                                src="{{ Storage::url($album->cover_image) }}"
                                                alt="{{ $album->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy"
                                            />
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100">
                                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm">Empty Album</span>
                                            </div>
                                        @endif

                                        {{-- Photo Count Badge --}}
                                        <div class="absolute bottom-4 right-4 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $album->galleries_count }} Photos
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-6 flex-1 flex flex-col">
                                        <h2 class="font-heading text-2xl text-foreground mb-2 group-hover:text-secondary transition-colors">
                                            {{ $album->title }}
                                        </h2>
                                        
                                        <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-2 leading-relaxed flex-1">
                                            {{ $album->description ?? 'No description available.' }}
                                        </p>

                                        <div class="flex items-center gap-2 text-sm text-foreground/50 mt-auto pt-4 border-t border-gray-100">
                                            <span>📅 {{ $album->created_at->format('d M Y') }}</span>
                                            <span class="mx-1">•</span>
                                            <span>By {{ $album->user->name }}</span>
                                        </div>
                                    </div>
                                </article>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $albums->links() }}
                    </div>

                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                        <div class="text-6xl mb-4">📷</div>
                        <h3 class="text-2xl font-heading text-gray-800">No albums found</h3>
                        <p class="text-gray-500 mt-2">Check back later for new updates.</p>
                        @if(request('search'))
                            <a href="{{ route('public.gallery.index') }}" class="inline-block mt-6 px-6 py-2 bg-secondary text-secondary-foreground rounded-full hover:opacity-90 transition font-bold">
                                Clear Search
                            </a>
                        @endif
                    </div>
                @endif
                
            </div>
        </section>
    </div>
@endsection