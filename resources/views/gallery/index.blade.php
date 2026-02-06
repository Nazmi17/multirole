<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Galeri') }}
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

                    <div class="mb-4">
                        <form action="{{ route('galleries.index') }}" method="GET">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari judul galeri..." 
                                    class="border rounded px-3 py-2 w-full max-w-xs"
                                >
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Cari
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('galleries.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                                        Reset
                                    </a>
                                @endif

                                <div class="flex items-center gap-2 ml-auto">
                                    <a href="{{ route('galleries.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white border-transparent font-bold py-2 px-4 rounded shadow-sm text-sm">
                                        + Tambah Galeri
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cover
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Album
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($galleries as $index => $gallery)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $galleries->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($gallery->image)
                                            <img src="{{ asset('storage/' . $gallery->image) }}" alt="Cover" class="h-12 w-16 object-cover rounded">
                                        @else
                                            <span class="text-xs text-gray-400 italic">No Image</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $gallery->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($gallery->album)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $gallery->album->title }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tanpa Album</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($gallery->categories as $category)
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800 border border-gray-200">
                                                    {{ $category->title }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('galleries.edit', $gallery->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            
                                            <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('Yakin hapus galeri ini beserta fotonya?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data galeri.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $galleries->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>