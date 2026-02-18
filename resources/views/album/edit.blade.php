<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Album: ') . $album->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bagian 1: Form Edit Data Utama & Tambah Foto --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="POST" action="{{ route('albums.update', $album->id) }}" enctype="multipart/form-data"
                    x-data="{ title: '{{ old('title', $album->title) }}' }">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Info Album</h3>
                            {{-- Input Judul, Deskripsi, Cover (SAMA SEPERTI SEBELUMNYA) --}}
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Nama Album</label>
                                <input type="text" name="title" id="title" x-model="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $album->description) }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Album</label>
                                @if($album->cover_image)
                                    <img src="{{ asset('storage/' . $album->cover_image) }}" class="h-20 w-32 object-cover rounded mb-2 border">
                                @endif
                                <input type="file" name="cover_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700">
                            </div>
                        </div>

                        {{-- Section Tambah Foto Baru --}}
                        <div class="border-l pl-0 md:pl-6 border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Tambah Foto Lain</h3>
                            <p class="text-xs text-gray-500 mb-3">Pilih foto dari galeri yang belum memiliki album:</p>
                            
                            @if($availableGalleries->count() > 0)
                                <div class="grid grid-cols-3 gap-2 max-h-80 overflow-y-auto p-2 border rounded bg-gray-50">
                                    @foreach($availableGalleries as $gallery)
                                        <div class="relative">
                                            <input type="checkbox" name="gallery_ids[]" value="{{ $gallery->id }}" id="add_gal_{{ $gallery->id }}" class="peer hidden">
                                            <label for="add_gal_{{ $gallery->id }}" class="block cursor-pointer border-2 border-transparent peer-checked:border-indigo-500 peer-checked:ring-2 rounded transition-all opacity-60 peer-checked:opacity-100 grayscale peer-checked:grayscale-0">
                                                <img src="{{ asset('storage/' . $gallery->image) }}" class="w-full h-20 object-cover">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak ada foto lain yang tersedia.</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 border-t pt-4">
                         <a href="{{ route('albums.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm flex items-center">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-sm text-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- Bagian 2: Kelola Foto yang SUDAH ADA di Album ini --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Foto di Album Ini ({{ $album->galleries->count() }})</h3>
                
                @if($album->galleries->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        @foreach($album->galleries as $gallery)
                            <div class="relative group border rounded-lg overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $gallery->image) }}" class="w-full h-32 object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    {{-- Form untuk mengeluarkan foto dari album --}}
                                    <form action="{{ route('albums.remove_gallery', ['album' => $album->id, 'gallery' => $gallery->id]) }}" method="POST" onsubmit="return confirm('Keluarkan foto ini dari album? (Foto tidak akan terhapus dari galeri utama)');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded shadow">
                                            Keluarkan
                                        </button>
                                    </form>
                                </div>
                                <div class="p-2 bg-white text-xs truncate font-medium text-gray-700">{{ $gallery->title }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm italic text-center py-8">Belum ada foto di album ini.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>