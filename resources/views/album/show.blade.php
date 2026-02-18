<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Album: ') . $album->title }}
            </h2>
            <a href="{{ route('albums.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian 1: Header & Info Album --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 md:flex gap-6">
                    {{-- Cover Album (Kiri) --}}
                    <div class="w-full md:w-1/4 mb-4 md:mb-0">
                        @if($album->cover_image)
                            <img src="{{ asset('storage/' . $album->cover_image) }}" 
                                 alt="Cover Album" 
                                 class="w-full h-auto rounded-lg shadow-md object-cover aspect-[4/3]">
                        @else
                            <div class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center border border-dashed text-gray-400">
                                No Cover
                            </div>
                        @endif
                    </div>

                    {{-- Detail Album (Kanan) --}}
                    <div class="w-full md:w-3/4 flex flex-col justify-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $album->title }}</h3>
                        
                        <div class="text-sm text-gray-500 mb-4 flex items-center gap-4">
                            <span>Oleh: <span class="font-semibold">{{ $album->user->name }}</span></span>
                            <span>&bull;</span>
                            <span>{{ $album->created_at->format('d M Y') }}</span>
                            <span>&bull;</span>
                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                                {{ $album->galleries->count() }} Foto
                            </span>
                        </div>

                        <div class="text-gray-700 prose max-w-none">
                            <p>{{ $album->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('albums.edit', $album->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Album / Kelola Foto
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Grid Galeri Foto --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4 ml-1">Isi Galeri</h3>

            @if($album->galleries->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($album->galleries as $gallery)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg group hover:shadow-md transition-shadow duration-200">
                            {{-- Gambar --}}
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ asset('storage/' . $gallery->image) }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                
                                {{-- Overlay Judul (Muncul saat hover) --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                    <p class="text-white font-medium text-sm truncate">{{ $gallery->title }}</p>
                                    @if($gallery->caption)
                                        <p class="text-gray-300 text-xs truncate">{{ $gallery->caption }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Bawah --}}
                            <div class="p-4">
                                <h4 class="font-bold text-gray-800 truncate mb-1" title="{{ $gallery->title }}">
                                    {{ $gallery->title }}
                                </h4>
                                
                                {{-- Kategori (Jika ada) --}}
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @forelse($gallery->categories as $category)
                                        <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded border border-gray-200">
                                            {{ $category->title }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic">Tanpa Kategori</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada foto</h3>
                    <p class="mt-1 text-sm text-gray-500">Album ini masih kosong.</p>
                    <div class="mt-6">
                        <a href="{{ route('albums.edit', $album->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Tambah Foto &rarr;
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>