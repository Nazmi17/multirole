<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Album') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 overflow-x-auto w-full">

                    {{-- Bagian Atas: Search dan Tambah --}}
                    <div class="mb-4">
                        <form action="{{ route('albums.index') }}" method="GET">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari album..." 
                                    class="border rounded px-3 py-2 w-full max-w-xs focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Cari
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('albums.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                                        Reset
                                    </a>
                                @endif

                                <div class="flex items-center gap-2 ml-auto">
                                    <a href="{{ route('albums.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-sm text-sm">
                                        + Tambah Album
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Album</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Foto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($albums as $index => $album)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $albums->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Thumbnail: Ambil foto pertama dari galeri di album ini --}}
                                        @if($album->cover_image)
                                            <img src="{{ asset('storage/' . $album->cover_image) }}" 
                                                alt="Cover" 
                                                class="h-12 w-16 object-cover rounded shadow-sm">
                                        @else
                                            <div class="h-12 w-16 bg-gray-100 rounded flex items-center justify-center border border-dashed border-gray-300">
                                                <span class="text-[10px] text-gray-400">Kosong</span>
                                            </div>
                                        @endif

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $album->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $album->galleries_count }} Foto
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $album->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            {{-- Link Buka Album (Opsional, untuk melihat isi foto) --}}
                                            <a href="{{ route('albums.show', $album->id) }}" class="text-green-600 hover:text-green-900">Buka</a>
                                            
                                            <a href="{{ route('albums.edit', $album->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            
                                            <form action="{{ route('albums.destroy', $album->id) }}" method="POST" onsubmit="return confirm('Yakin hapus album ini? Foto-foto di dalamnya tidak akan terhapus, hanya albumnya yang hilang.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data album.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $albums->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>