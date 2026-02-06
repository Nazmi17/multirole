<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Hak Akses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Statistik & Alert --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-between items-center">
                <p class="text-gray-900 font-bold text-lg">Total Users: {{ $totalUsers }}</p>
                <p class="text-gray-500 text-sm">Total Roles: {{ $roles->count() }}</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: Form Buat Role Baru --}}
                <div class="md:col-span-1">
                    <div class="bg-white p-6 shadow-md sm:rounded-lg sticky top-6">
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-900">🚀 Buat Role Baru</h3>
                            <p class="text-xs text-gray-500 mt-1">Buat role dan atur akses sekaligus.</p>
                        </div>
                        
                        <form action="{{ route('admin.roles.store') }}" method="POST">
                            @csrf
                            
                            {{-- Input Nama Role --}}
                            <div class="mb-5">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                                <input type="text" name="name" id="name" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                                    placeholder="contoh: supervisor" required>
                            </div>

                            {{-- SEARCHABLE PERMISSION LIST --}}
                            {{-- Kita gunakan x-data untuk menyimpan state pencarian permission --}}
                            <div class="mb-6" x-data="{ permSearch: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Hak Akses:</label>
                                
                                {{-- Input Search Permission --}}
                                <div class="relative mb-2">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                        x-model="permSearch" 
                                        class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5" 
                                        placeholder="Filter permission...">
                                    
                                    {{-- Tombol Clear Search --}}
                                    <button type="button" 
                                            x-show="permSearch.length > 0" 
                                            @click="permSearch = ''"
                                            class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600">
                                        &times;
                                    </button>
                                </div>

                                {{-- Container List --}}
                                <div class="h-48 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50 space-y-2 relative">
                                    
                                    @foreach($permissions as $permission)
                                        <div class="flex items-center" 
                                            {{-- Logic Filtering: Sembunyikan item jika namanya tidak cocok dengan input search --}}
                                            x-show="'{{ strtolower($permission->name) }}'.includes(permSearch.toLowerCase())"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100">
                                            
                                            <input id="new_perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" type="checkbox" 
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer flex-shrink-0">
                                            <label for="new_perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-600 cursor-pointer select-none break-all">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach

                                    {{-- Pesan jika tidak ditemukan (Logic JS) --}}
                                    <div x-show="$el.parentElement.querySelectorAll('div[x-show]:not([style*=\'display: none\'])').length === 0" 
                                        class="text-center text-gray-400 text-xs py-4" style="display: none;">
                                        Tidak ada permission yang cocok.
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 flex justify-between">
                                    <span>*Scroll untuk melihat lebih banyak</span>
                                </p>
                            </div>

                            <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Role Baru
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: List Role & Edit (Existing) --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                        
                        {{-- HEADER: Judul & Form Search --}}
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <h3 class="text-lg font-bold text-gray-900">📋 Daftar Role & Akses</h3>
                            
                            {{-- Search Form --}}
                            <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                                    placeholder="Cari nama role...">
                                
                                {{-- Tombol Reset (Muncul jika sedang mencari) --}}
                                @if(request('search'))
                                    <a href="{{ url()->current() }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            {{-- Handle jika hasil pencarian kosong --}}
                            @if($roles->isEmpty())
                                <div class="p-6 text-center text-gray-500">
                                    <p>Tidak ditemukan role dengan nama "<strong>{{ request('search') }}</strong>".</p>
                                    <a href="{{ url()->current() }}" class="text-indigo-600 hover:underline mt-2 inline-block">Reset Pencarian</a>
                                </div>
                            @else
                                @foreach($roles as $role)
                                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                                        {{-- Header per Role --}}
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-xl font-bold text-gray-800 capitalize flex items-center">
                                                    {{ $role->name }}
                                                    @if($role->name === 'admin')
                                                        <span class="ml-2 px-2 py-0.5 text-[10px] bg-red-100 text-red-800 rounded-full border border-red-200">System</span>
                                                    @endif
                                                </h4>
                                                <span class="text-xs text-gray-500">ID: {{ $role->id }}</span>
                                            </div>
                                            
                                            @if($role->name !== 'admin')
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Yakin hapus role {{ $role->name }}? User dengan role ini akan kehilangan akses.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Form Update Permission --}}
                                        <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            {{-- Alpine Component: Kita inisialisasi state search di sini --}}
                                            <div class="mb-4" x-data="{ editSearch: '' }">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Hak Akses:</label>
                                                
                                                {{-- 1. INPUT SEARCH (Style sama persis dengan kolom kiri) --}}
                                                <div class="relative mb-2">
                                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <input type="text" 
                                                        x-model="editSearch" 
                                                        class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5" 
                                                        placeholder="Filter permission...">
                                                    
                                                    <button type="button" 
                                                            x-show="editSearch.length > 0" 
                                                            @click="editSearch = ''"
                                                            class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600">
                                                        &times;
                                                    </button>
                                                </div>

                                                {{-- 2. LIST PERMISSIONS (Scrollable box, bukan Grid lagi, agar konsisten) --}}
                                                <div class="h-48 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50 space-y-2 relative">
                                                    
                                                    @foreach($permissions as $permission)
                                                        <div class="flex items-center" 
                                                            {{-- Logic Filter: Sembunyikan jika tidak cocok --}}
                                                            x-show="'{{ strtolower($permission->name) }}'.includes(editSearch.toLowerCase())"
                                                            x-transition:enter="transition ease-out duration-200"
                                                            x-transition:enter-start="opacity-0 transform scale-95"
                                                            x-transition:enter-end="opacity-100 transform scale-100">
                                                            
                                                            <input 
                                                                id="role_{{ $role->id }}_perm_{{ $permission->id }}" 
                                                                name="permissions[]" 
                                                                value="{{ $permission->name }}" 
                                                                type="checkbox" 
                                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer flex-shrink-0"
                                                                {{-- Server-side check: Centang jika role sudah punya permission ini --}}
                                                                @checked($role->hasPermissionTo($permission->name))
                                                            >
                                                            <label for="role_{{ $role->id }}_perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-600 cursor-pointer select-none break-all">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach

                                                    {{-- Pesan jika pencarian tidak ditemukan --}}
                                                    <div x-show="$el.parentElement.querySelectorAll('div[x-show]:not([style*=\'display: none\'])').length === 0" 
                                                        class="text-center text-gray-400 text-xs py-4" style="display: none;">
                                                        Tidak ada permission yang cocok.
                                                    </div>
                                                </div>
                                                <p class="text-xs text-gray-400 mt-1 flex justify-between">
                                                    <span>*Hanya menampilkan hasil filter (data tercentang tetap tersimpan)</span>
                                                </p>
                                            </div>
                                            
                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                    Update Akses
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>