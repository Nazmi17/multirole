<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload E-Book Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Validasi Error --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('ebooks.store') }}" enctype="multipart/form-data"
                x-data="{
                    title: '{{ old('title') }}',
                    file: null,
                    get isComplete() {
                        // Tombol aktif jika Judul terisi (File tidak bisa divalidasi lgsg via alpine tanpa trick, jadi kita validasi judul saja untuk UX basic)
                        return this.title.trim().length > 0;
                    }
                }">
                
                    @csrf

                    {{-- Title --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul E-Book</label>
                        <input type="text" name="title" id="title" placeholder="Masukkan Judul E-Book" x-model="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                    </div>

                    {{-- File PDF --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">File PDF (Wajib)</label>
                        <input type="file" name="file" accept="application/pdf" required
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">Maksimal 20MB.</p>
                    </div>

                    {{-- Cover Image --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cover Gambar (Opsional)</label>
                        <input type="file" name="cover_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    {{-- Checkbox Login --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input type="checkbox" name="is_login_required" id="is_login_required" value="1" {{ old('is_login_required') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_login_required" class="font-medium text-gray-700">Wajib Login?</label>
                                <p class="text-gray-500 text-xs">Jika dicentang, hanya user login yang bisa download.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end mt-4 gap-6">
                        <a href="{{ route('ebooks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm justify-self-start">Batal</a>
                        
                        <button type="submit" 
                            :disabled="!isComplete"
                            :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                            Upload
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>