<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen E-Book') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 overflow-x-auto w-full">

                    {{-- Toolbar: Search & Add Button --}}
                    <div class="mb-4">
                        <form action="{{ route('ebooks.index') }}" method="GET">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari judul e-book..." 
                                    class="border rounded px-3 py-2 w-full max-w-xs"
                                >
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Cari
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('ebooks.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                                        Reset
                                    </a>
                                @endif

                                <div class="flex items-center gap-2 ml-auto">
                                    @can('create ebooks', \App\Models\Ebook::class)
                                        <a href="{{ route('ebooks.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white border-transparent font-bold py-2 px-4 rounded shadow-sm text-sm">
                                            + Upload E-Book
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Table --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul & Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akses</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($ebooks as $index => $ebook)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ebooks->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ebook->cover_image)
                                            <img src="{{ Storage::url($ebook->cover_image) }}" class="h-12 w-10 object-cover rounded border">
                                        @else
                                            <div class="h-12 w-10 bg-gray-100 flex items-center justify-center text-xs text-gray-400 border rounded">PDF</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $ebook->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $ebook->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ebook->is_login_required)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Locked
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Public
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ebook->creator->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            @if($ebook->is_login_required)
                                                <a href="{{ route('ebooks.history', $ebook->id) }}" 
                                                class="text-teal-600 hover:text-teal-900 border border-teal-200 bg-teal-50 px-2 py-1 rounded text-xs flex items-center gap-1"
                                                title="Lihat siapa yang download">
                                                🕒 History
                                                </a>
                                            @endif

                                            @can('update', $ebook)
                                                <a href="{{ route('ebooks.edit', $ebook->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endcan
                                            
                                            @can('delete', $ebook)
                                                <form action="{{ route('ebooks.destroy', $ebook->id) }}" method="POST" onsubmit="return confirm('Yakin hapus e-book ini?');" class="inline ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data E-Book.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $ebooks->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>