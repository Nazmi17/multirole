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
                        title: '{{ old('title', $gallery->title) }}',
                        get isComplete() {
                            return this.title.trim().length > 0;
                        }
                    }">
                    
                    @csrf
                    @method('PUT')

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

                    {{-- Upload Gambar (Edit) --}}
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Ganti Cover (Opsional)</label>
                        
                        {{-- Preview Gambar Lama --}}
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

                    <div class="mb-4">
                        <label for="album_id" class="block text-sm font-medium text-gray-700">Album (Opsional)</label>
                        <select name="album_id" id="album_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            
                            <option value="">-- Tanpa Album --</option>
                            
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}" 
                                    {{-- Cek apakah ID album cocok dengan data gallery saat ini --}}
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