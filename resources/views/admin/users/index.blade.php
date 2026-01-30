<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen User') }}
            </h2>
            
            <div class="flex items-center gap-2">
                {{-- TOMBOL TRASH (BARU) --}}
                @can('delete users')
                    <a href="{{ route('admin.users.trash') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow-sm text-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Sampah ({{ \App\Models\User::onlyTrashed()->count() }})
                    </a>
                @endcan

                {{-- TOMBOL TAMBAH --}}
                @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white border-transparent font-bold py-2 px-4 rounded shadow-sm text-sm">
                        + Tambah User
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 overflow-x-auto">

                    <div class="mb-4">
                        <form action="{{ route('admin.users.index') }}" method="GET">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari nama atau email..." 
                                    class="border rounded px-3 py-2 w-full max-w-xs"
                                >
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Cari
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Avatar
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Username
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sedang
                                </th>
                                 <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bergabung
                                </th> --}}
                                @canany(['edit users', 'delete users'])
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img 
                                            src="{{ $user->avatar_url }}" 
                                            class="rounded-full h-10 w-10 object-cover"
                                        >            
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($user->getRoleNames() as $role)
                                            {{-- Tentukan Warna Badge --}}
                                            @php
                                                $badgeClass = match($role) {
                                                    'admin'     => 'bg-red-100 text-red-800',   
                                                    'Pengelola' => 'bg-blue-100 text-blue-800', 
                                                    default     => 'bg-green-100 text-green-800' 
                                                };

                                                $roleName = match($role) {
                                                    'admin'     => 'Super Admin',
                                                    'Pengelola' => 'Admin',
                                                    default     => ucfirst($role)
                                                };
                                            @endphp

                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                                {{ $roleName }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            {{-- Indikator Bulat --}}
                                            @if($user->isOnline())
                                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 animate-pulse" title="Sedang Online"></div>
                                                <span class="text-xs font-semibold text-green-600">Online</span>
                                            @else
                                                <div class="h-2.5 w-2.5 rounded-full bg-gray-400" title="Offline"></div>
                                                <span class="text-xs text-gray-500">
                                                    {{-- Jika kamu punya kolom last_seen di DB, bisa tampilkan di sini --}}
                                                    Offline
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            
                                           {{-- TOMBOL EDIT --}}
                                            {{-- Laravel otomatis cek Policy: update($currentUser, $targetUser) --}}
                                            @can('update', $user)
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endcan
                                            
                                            &nbsp; &nbsp;

                                            {{-- TOMBOL DELETE --}}
                                            {{-- Laravel otomatis cek Policy: delete($currentUser, $targetUser) --}}
                                            @can('delete', $user)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                                </form>
                                            @endcan

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>