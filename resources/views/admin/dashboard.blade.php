<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Hak Akses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-900 font-bold">Total Users: {{ $totalUsers }}</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white p-6 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Buat Role Baru</h3>
                        <div class="devide-y divide-gray-200">
                            <form action="{{ route('admin.roles.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Role</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ex: editor, manager" required>
                            </div>
                            <button type="submit" class="w-6/12 inline-flex justify-center rounded-md border bg-indigo-600 py-2 px-4 text-sm font-medium text-black shadow-sm hover:bg-indigo-700 focus:outline-black focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 border-black">
                                Simpan Role
                            </button>
                        </form>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 pt-6">
                    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Manajemen Hak Akses per Role</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($roles as $role)
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-lg font-bold text-gray-800 capitalize flex items-center">
                                            Role: {{ $role->name }}
                                            @if($role->name === 'admin')
                                                <span class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">Super User</span>
                                            @endif
                                        </h4>
                                        
                                        @if($role->name !== 'admin')
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Yakin hapus role ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Hapus Role</button>
                                            </form>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-12 mb-4">
                                        @foreach($permissions as $permission)
                                            {{-- Menambahkan gap-x-3 untuk memberi jarak horizontal --}}
                                            <div class="flex items-start gap-y-20"> 
                                                <div class="flex h-5 items-center">
                                                    <input 
                                                        id="perm_{{ $role->id }}_{{ $permission->id }}" 
                                                        name="permissions[]" 
                                                        value="{{ $permission->name }}" 
                                                        type="checkbox" 
                                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        {{-- Cek apakah role ini punya permission tersebut --}}
                                                        @checked($role->hasPermissionTo($permission->name))
                                                    >
                                                </div>
                                                &nbsp;
                                                {{-- Menghapus ml-3 karena sudah digantikan oleh gap-x-3 di parent --}}
                                                <div class="text-sm">
                                                    <label for="perm_{{ $role->id }}_{{ $permission->id }}" class="font-medium text-gray-700 select-none cursor-pointer">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                        
                                        <div class="text-right">
                                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">
                                                Update Akses {{ ucfirst($role->name) }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>