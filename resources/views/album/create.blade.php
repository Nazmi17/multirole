<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Album Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data"
                    x-data="{ 
                        title: '{{ old('title') }}',
                        get isComplete() { return this.title.trim().length > 0; }
                    }">
                    @csrf

                    {{-- Judul Album --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Nama Album</label>
                        <input type="text" name="title" id="title" x-model="title" required
                            placeholder="Contoh: Liburan Musim Panas 2024"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Deskripsi Album --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" 
                            placeholder="Ceritakan singkat tentang isi album ini..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Upload Cover Image --}}
                    <div class="mb-4">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Album</label>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maks: 5MB</p>
                        @error('cover_image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-6">
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

<script>
    document.addEventListener("DOMContentLoaded", function(){
        new TomSelect("#select-categories",{
            create: false,
            sortField: { field: "text", direction: "asc" },
            plugins: ['remove_button'],
        });
    });
</script>