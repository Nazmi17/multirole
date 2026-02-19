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
                            <div class="break-inside-avoid relative group rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition duration-300 bg-gray-100 border border-gray-200">
                                
                                {{-- CEK APAKAH ADA VIDEO URL --}}
                                @if($photo->video_url && $embed = $photo->video_embed)

                                    {{-- A. YOUTUBE (16:9 Landscape) --}}
                                    @if($embed['type'] == 'youtube')
                                        <div class="relative w-full aspect-video">
                                            <iframe src="{{ $embed['url'] }}" class="absolute top-0 left-0 w-full h-full" frameborder="0" allowfullscreen></iframe>
                                        </div>

                                    {{-- B. INSTAGRAM (Biasanya Kotak atau Vertikal) --}}
                                    @elseif($embed['type'] == 'instagram')
                                        {{-- Instagram butuh tinggi fix atau lebih besar. Kita set min-height --}}
                                        <div class="w-full overflow-hidden bg-white">
                                            <iframe src="{{ $embed['url'] }}" class="w-full aspect-[4/5] md:aspect-[9/16]" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                                        </div>

                                    {{-- C. TIKTOK (Vertikal) --}}
                                    @elseif($embed['type'] == 'tiktok')
                                        <div class="w-full overflow-hidden bg-black">
                                            <iframe src="{{ $embed['url'] }}" class="w-full h-[550px]" frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                    
                                    {{-- JUDUL (Ditaruh di bawah video agar tidak menutupi player) --}}
                                    <div class="p-4 bg-white border-t">
                                        <h3 class="font-heading text-lg font-bold text-gray-800 line-clamp-1">
                                            {{-- Icon Kecil Penanda Platform --}}
                                            @if($embed['type'] == 'youtube') <span class="text-red-600">▶</span>
                                            @elseif($embed['type'] == 'instagram') <span class="text-pink-600">📷</span>
                                            @elseif($embed['type'] == 'tiktok') <span class="text-black">🎵</span>
                                            @endif
                                            {{ $photo->title }}
                                        </h3>
                                        @if($photo->caption)
                                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $photo->caption }}</p>
                                        @endif
                                    </div>

                                @else
                                    {{-- FALLBACK: FOTO BIASA / LINK VIDEO NON-EMBED --}}
                                    <div class="relative">
                                        <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500">
                                        
                                        {{-- Jika video tapi tidak terdeteksi (misal link Google Drive), tetap kasih tombol play --}}
                                        @if($photo->video_url)
                                            <a href="{{ $photo->video_url }}" target="_blank" class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/50 transition z-10">
                                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6 text-gray-800 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </div>
                                            </a>
                                            <div class="absolute top-2 right-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded font-bold z-10">EXTERNAL LINK</div>
                                        @endif
                                        
                                        {{-- Overlay Judul untuk Foto --}}
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 pointer-events-none">
                                            <h3 class="text-white font-heading text-lg font-bold">{{ $photo->title }}</h3>
                                            @if($photo->caption)
                                                <p class="text-gray-200 text-sm font-paragraph line-clamp-2 mt-1">{{ $photo->caption }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif

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