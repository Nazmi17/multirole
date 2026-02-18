@extends('layouts-pages.app')

@section('content')
    <div class="min-h-screen bg-background">
        
        {{-- HERO SECTION --}}
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    E-Books & Modul
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    A collection of digital learning materials that can be downloaded directly.
                </p>
            </div>
        </section>

        {{-- CONTENT SECTION --}}
        <section class="w-full py-16 md:py-24">
            <div class="max-w-[100rem] mx-auto px-6">
                
                @if($ebooks->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($ebooks as $ebook)
                            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 h-full flex flex-col group relative">
                                
                                {{-- 1. IMAGE WRAPPER (Updated: Aspect Ratio & Styling sama dengan Article) --}}
                                <div class="relative aspect-[700/450] overflow-hidden bg-gray-200">
                                    {{-- Kita bungkus dengan link download agar bisa diklik --}}
                                    <a href="{{ route('ebooks.stream', $ebook->id) }}" class="block w-full h-full">
                                        @if($ebook->cover_image)
                                            <img
                                                src="{{ Storage::url($ebook->cover_image) }}"
                                                alt="{{ $ebook->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy"
                                            />
                                        @else
                                            {{-- Fallback jika tidak ada cover --}}
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Badge Status (Posisi & Style disesuaikan) --}}
                                    <div class="absolute top-4 left-4 pointer-events-none"> {{-- pointer-events-none agar klik tembus ke gambar --}}
                                        @if($ebook->is_login_required)
                                            @if(!auth()->check())
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1 border border-yellow-200">
                                                🔒 Login Required
                                            </span>
                                            @else
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1 border border-green-200">
                                                🌍 Sudah Bisa Didownload
                                            </span>
                                            @endif
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center gap-1 border border-green-200">
                                                🌍 Free Access
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 2. CONTENT INFO --}}
                                <div class="p-6 flex-1 flex flex-col">
                                    <h3 class="font-heading text-xl text-foreground mb-2 line-clamp-2 group-hover:text-secondary transition-colors">
                                        {{-- Judul juga bisa diklik --}}
                                        <a href="{{ route('ebooks.stream', $ebook->id) }}">
                                            {{ $ebook->title }}
                                        </a>
                                    </h3>
                                    
                                    <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-3 leading-relaxed flex-1">
                                        {{ $ebook->description ?? 'Tidak ada deskripsi tersedia.' }}
                                    </p>

                                   <div class="border-t border-gray-100 pt-4 mt-auto">
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                            <span class="flex items-center gap-1">
                                                👤 {{ $ebook->creator->name ?? 'Admin' }}
                                            </span>
                                            <span>
                                                📅 {{ $ebook->created_at->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            {{-- TOMBOL BACA (Mata) --}}
                                            <a href="{{ route('ebooks.stream', $ebook->id) }}" target="_blank"
                                            class="flex items-center justify-center gap-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold py-3 rounded-xl transition shadow-sm border border-indigo-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Baca
                                            </a>

                                            {{-- TOMBOL DOWNLOAD (Panah Bawah) --}}
                                            <a href="{{ route('ebooks.download', $ebook->id) }}" 
                                            class="flex items-center justify-center gap-2 bg-secondary hover:bg-opacity-90 text-secondary-foreground font-bold py-3 rounded-xl transition shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Unduh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12">
                        {{ $ebooks->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="text-2xl font-heading text-gray-800">Belum ada E-Book</h3>
                        <p class="text-gray-500 mt-2">Silakan cek kembali nanti.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection