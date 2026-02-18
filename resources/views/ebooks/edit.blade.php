<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit E-Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('ebooks.update', $ebook->id) }}" enctype="multipart/form-data"
                 x-data="{ title: '{{ old('title', $ebook->title) }}' }">
                
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul E-Book</label>
                        <input type="text" name="title" id="title" x-model="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $ebook->description) }}</textarea>
                    </div>

                    {{-- File PDF --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">File PDF</label>
                        <div class="text-xs text-green-600 mb-2 font-bold">✓ File saat ini sudah ada. Upload baru jika ingin mengganti.</div>
                        <input type="file" name="file" accept="application/pdf"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    {{-- Cover Image --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cover Gambar</label>
                        @if($ebook->cover_image)
                            <img src="{{ Storage::url($ebook->cover_image) }}" class="h-20 w-auto mb-2 rounded border">
                        @endif
                        <input type="file" name="cover_image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    {{-- Checkbox Login --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div class="flex items-start">
                            <div class="flex h-5 items-center">
                                <input type="checkbox" name="is_login_required" id="is_login_required" value="1" 
                                {{ old('is_login_required', $ebook->is_login_required) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_login_required" class="font-medium text-gray-700">Wajib Login?</label>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end mt-4 gap-6">
                        <a href="{{ route('ebooks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm justify-self-start">Batal</a>
                        
                        <button type="submit" 
                            :disabled="title.length === 0"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>