<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('categories.store') }}" 
                x-data="{
                    title: '{{ old('title') }}',
                    get isComplete() {
                        return this.title.trim().length > 0;
                    }
                }">
                
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Kategori</label>
                        <input type="text" name="title" id="title" placeholder="Masukkan Judul Kategori" x-model="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-6">
                        <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm justify-self-start">Batal</a>
                        
                        <button type="submit" 
                            :disabled="!isComplete"
                            :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                            Simpan Kategori
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>