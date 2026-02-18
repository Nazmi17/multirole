<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Album Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Lebarkan dikit jadi max-w-4xl --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data"
                    x-data="{ 
                        title: '{{ old('title') }}',
                        get isComplete() { return this.title.trim().length > 0; }
                    }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom Kiri: Info Album --}}
                        <div>
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Nama Album</label>
                                <input type="text" name="title" id="title" x-model="title" required
                                    placeholder="Contoh: Liburan Musim Panas 2024"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Album</label>
                                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100">
                                @error('cover_image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan: Pilih Foto --}}
                        <div class="border-l pl-0 md:pl-6 border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto (Opsional)</label>
                            <p class="text-xs text-gray-500 mb-3">Pilih foto dari galeri yang belum masuk album manapun.</p>
                            
                            @if($galleries->count() > 0)
                                <div class="grid grid-cols-3 gap-2 max-h-96 overflow-y-auto p-2 border rounded bg-gray-50">
                                    @foreach($galleries as $gallery)
                                        <div class="relative">
                                            <input type="checkbox" name="gallery_ids[]" value="{{ $gallery->id }}" id="gal_{{ $gallery->id }}" class="peer hidden">
                                            <label for="gal_{{ $gallery->id }}" class="block cursor-pointer border-2 border-transparent peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-200 rounded-lg overflow-hidden transition-all opacity-70 peer-checked:opacity-100">
                                                <img src="{{ asset('storage/' . $gallery->image) }}" class="w-full h-24 object-cover">
                                            </label>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-[10px] p-1 truncate text-center pointer-events-none">
                                                {{ $gallery->title }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded text-center border border-dashed">
                                    Tidak ada foto tersedia (semua sudah masuk album).
                                    <a href="{{ route('galleries.create') }}" class="text-indigo-600 underline">Upload foto dulu?</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 gap-6">
                        <a href="{{ route('albums.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">Batal</a>
                        <button type="submit" 
                            :disabled="!isComplete"
                            :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                            Simpan Album
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>