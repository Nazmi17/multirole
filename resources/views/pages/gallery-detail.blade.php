@extends('layouts-pages.app')

@section('content')
    <div class="min-h-screen bg-background">
        
        {{-- HERO HEADER --}}
        <section class="w-full bg-primary pt-32 pb-10 md:pt-40 md:pb-16 text-center px-6">
            <a href="{{ route('public.gallery.index') }}" class="inline-flex items-center text-sm font-bold text-foreground/60 hover:text-secondary mb-4 transition">
                ← Back to Gallery
            </a>
            <h1 class="font-heading text-4xl md:text-5xl text-foreground mb-4">
                {{ $album->title }}
            </h1>
            <p class="font-paragraph text-lg text-foreground/80 max-w-2xl mx-auto">
                {{ $album->description }}
            </p>
            <div class="mt-4 text-sm text-foreground/60">
                Published on {{ $album->created_at->format('d F Y') }}
            </div>
        </section>

        {{-- PHOTOS GRID --}}
        <section class="w-full py-12 md:py-16">
            <div class="max-w-[100rem] mx-auto px-6">
                
                @if($album->galleries->count() > 0)
                    {{-- Masonry-like Grid using Column count for varying aspect ratios --}}
                    <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                        @foreach ($album->galleries as $photo)
                            <div class="break-inside-avoid relative group rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition duration-300 bg-gray-100">
                                <img
                                    src="{{ Storage::url($photo->image) }}"
                                    alt="{{ $photo->title }}"
                                    class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500"
                                    loading="lazy"
                                />
                                
                                {{-- Overlay Info --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                    <h3 class="text-white font-heading text-lg font-bold">
                                        {{ $photo->title }}
                                    </h3>
                                    @if($photo->caption)
                                        <p class="text-gray-200 text-sm font-paragraph line-clamp-2 mt-1">
                                            {{ $photo->caption }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20">
                        <p class="text-gray-500 italic">No photos available in this album yet.</p>
                    </div>
                @endif

            </div>
        </section>
    </div>
@endsection