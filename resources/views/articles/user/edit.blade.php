<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Article') }}
        </h2>
    </x-slot>

    {{-- CSS TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Khusus jika status Rejected (Ditolak Editor) --}}
            @if($article->status === 'rejected')
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <span class="font-bold">Perlu Revisi:</span>
                                Artikel ini dikembalikan oleh editor.
                            </p>
                            <p class="text-sm text-red-600 mt-1 italic">
                                " {{ $article->admin_feedback }} "
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $article->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="select-categories" :value="__('Categories')" class="mb-1" />
                            
                            <select id="select-categories" name="category_ids[]" multiple placeholder="Pilih kategori..." autocomplete="off" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{-- Logika: Cek data input lama (old) ATAU data dari database jika belum ada old input --}}
                                        {{ in_array($category->id, old('category_ids', $article->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_ids')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="featured_image" :value="__('Cover Image')" />
                            
                            {{-- Tampilkan gambar saat ini jika ada --}}
                            @if($article->featured_image)
                                <div class="mb-2">
                                    <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                                    <img src="{{ Storage::url($article->featured_image) }}" alt="Current Cover" class="h-32 rounded-md object-cover border border-gray-200">
                                </div>
                            @endif

                            <input id="featured_image" type="file" name="featured_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-1" />
                            <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar sampul.</p>
                            <p class="mt-1 text-xs text-gray-500">
                                Format: JPG, PNG, WEBP. Maks: 2MB. 
                                <br>
                                <span class="text-indigo-600 font-medium">
                                    💡 Tips: Gunakan gambar berdimensi <strong>700x450 pixels</strong> agar tampilan maksimal (tidak terpotong).
                                </span>
                            </p>
                            <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="excerpt" :value="__('Excerpt / Ringkasan Singkat')" />
                            <textarea id="excerpt" name="excerpt" rows="3" 
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Tulis ringkasan artikel ini (opsional). Jika dikosongkan, akan diambil otomatis dari konten.">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="editor" :value="__('Content')" />
                            <textarea id="editor" name="content">{{ old('content', $article->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div x-data="{ open: false }" class="mb-4 border rounded-md p-4 bg-gray-50">
                            <button type="button" @click="open = !open" class="flex justify-between items-center w-full text-left font-semibold text-gray-700 focus:outline-none">
                                <span>Pengaturan SEO (Opsional)</span>
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="mt-4 space-y-4" style="display: none;">
                                
                                <div>
                                    <x-input-label for="meta_title" :value="__('Meta Title')" />
                                    <x-text-input id="meta_title" class="block mt-1 w-full" type="text" name="meta_title" :value="old('meta_title', $article->meta_title ?? '')" placeholder="Judul yang tampil di Google search" />
                                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika ingin menggunakan judul artikel asli.</p>
                                </div>

                                <div>
                                    <x-input-label for="meta_description" :value="__('Meta Description')" />
                                    <textarea id="meta_description" name="meta_description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Ringkasan artikel untuk hasil pencarian (maks 160 karakter disarankan).</p>
                                </div>

                                <div>
                                    <x-input-label for="meta_keywords" :value="__('Meta Keywords')" />
                                    <x-text-input id="meta_keywords" class="block mt-1 w-full" type="text" name="meta_keywords" :value="old('meta_keywords', $article->meta_keywords ?? '')" placeholder="contoh: laravel, tutorial, php" />
                                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Article') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        // Init CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: "{{ route('articles.upload_image', ['_token' => csrf_token()]) }}"
                }
            })
            .catch(error => {
                console.error(error);
            });

        // Init TomSelect
        document.addEventListener("DOMContentLoaded", function(){
            new TomSelect("#select-categories",{
                create: false,
                sortField: { field: "text", direction: "asc" },
                plugins: ['remove_button'],
                maxOptions: null,
            });
        });
    </script>
    
    <style>
        /* Mengatur tinggi minimal editor */
        .ck-editor__editable_inline {
            min-height: 300px;
        }
        /* Style tambahan untuk TomSelect agar selaras dengan Tailwind */
        .ts-control {
            border-radius: 0.375rem; /* rounded-md */
            border-color: #d1d5db; /* border-gray-300 */
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .ts-control.focus {
            border-color: #6366f1; /* focus:border-indigo-500 */
            box-shadow: 0 0 0 1px #6366f1; /* focus:ring-indigo-500 */
        }
    </style>
</x-app-layout>