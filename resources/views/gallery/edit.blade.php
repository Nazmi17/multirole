<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Galeri: ') . $gallery->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('galleries.update', $gallery->id) }}" enctype="multipart/form-data"
                    x-data="{ 
                        {{-- LOGIC: Cek apakah data lama punya video_url, jika ya set type 'video', jika tidak 'photo' --}}
                        type: '{{ old('type', $gallery->video_url ? 'video' : 'photo') }}',
                        title: '{{ old('title', $gallery->title) }}',
                        get isComplete() {
                            return this.title.trim().length > 0;
                        }
                    }">
                    
                    @csrf
                    @method('PUT')

                    {{-- PILIHAN TIPE KONTEN (WAJIB ADA AGAR CONTROLLER TIDAK ERROR) --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="type" value="photo" x-model="type" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Foto</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="type" value="video" x-model="type" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Video (YouTube/TikTok/IG)</span>
                            </label>
                        </div>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Judul --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Galeri</label>
                        <input type="text" name="title" id="title" x-model="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label for="caption" class="block text-sm font-medium text-gray-700">Caption (Opsional)</label>
                        <textarea name="caption" id="caption" rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('caption', $gallery->caption) }}</textarea>
                    </div>

                    {{-- AREA INPUT FOTO (Hanya muncul jika type == photo) --}}
                    <div x-show="type === 'photo'" class="mb-4 bg-gray-50 p-4 rounded border">
                        <label for="image" class="block text-sm font-medium text-gray-700">Ganti Foto (Opsional)</label>
                        
                        @if($gallery->image)
                            <div class="mb-2">
                                <span class="text-xs text-gray-500 block mb-1">Gambar saat ini:</span>
                                <img src="{{ asset('storage/' . $gallery->image) }}" alt="Current Image" class="h-32 w-auto object-cover rounded border">
                            </div>
                        @endif

                        <input type="file" name="image" id="image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                        @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- AREA INPUT VIDEO (Hanya muncul jika type == video) --}}
                    <div x-show="type === 'video'" class="mb-4 bg-indigo-50 p-4 rounded border border-indigo-100" style="display: none;">
                        <label for="video_url" class="block text-sm font-medium text-gray-700">Link Video</label>
                        <input type="url" name="video_url" id="video_url" 
                            value="{{ old('video_url', $gallery->video_url) }}"
                            placeholder="https://tiktok.com/... atau https://instagram.com/..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Link TikTok/Instagram/YouTube.</p>
                        @error('video_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        {{-- Input Cover Manual untuk Video (Opsional) --}}
                        <div class="mt-3 pt-3 border-t border-indigo-200">
                            <label class="block text-sm font-medium text-gray-700">Ganti Cover Video (Opsional)</label>
                            @if($gallery->image && $gallery->video_url)
                                <img src="{{ asset('storage/' . $gallery->image) }}" class="h-20 w-auto object-cover rounded border mb-2">
                            @endif
                            <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="album_id" class="block text-sm font-medium text-gray-700">Album (Opsional)</label>
                        <select name="album_id" id="album_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Tanpa Album --</option>
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}" 
                                    {{ (old('album_id', $gallery->album_id) == $album->id) ? 'selected' : '' }}>
                                    {{ $album->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('album_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="categories" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="select-categories" name="categories[]" multiple placeholder="Pilih kategori..." autocomplete="off" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ in_array($category->id, old('categories', $gallery->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-6">
                        <a href="{{ route('galleries.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Batal</a>
                        <button type="submit" 
                            :disabled="!isComplete"
                            :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        new TomSelect("#select-categories",{
            create: false,
            sortField: { field: "text", direction: "asc" },
            plugins: ['remove_button'],
        });
    });
</script>