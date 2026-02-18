<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Articles') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900">
                    <div class="flex gap-3">
                        <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Write New Article') }}
                        </a>
                        
                        <a href="{{ route('articles.trash') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            🗑️ Trash
                        </a>
                    </div>
                    <div class="py-1"></div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($articles as $article)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($article->title, 30) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($article->categories as $category)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $category->title }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $colors = [
                                                    'draft' => 'bg-gray-100 text-gray-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'published' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$article->status] }}">
                                                {{ ucfirst($article->status) }}
                                            </span>
                                            @if($article->status === 'rejected')
                                                <p class="text-xs text-red-500 mt-1 cursor-help" title="{{ $article->admin_feedback }}">See Reason</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $article->updated_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <div class="flex justify-end items-center gap-2">
                                                {{-- Tombol Submit --}}
                                                @if($article->status === 'draft')
                                                    <form action="{{ route('articles.submit', $article) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900" onclick="return confirm('Apakah anda yakin? Anda tidak bisa mengubah artikel ini saat status pending.')">Submit</button>
                                                    </form>
                                                    <span class="text-gray-300">|</span>
                                                @endif

                                                {{-- Tombol Edit --}}
                                                @if(in_array($article->status, ['draft', 'rejected']))
                                                    <a href="{{ route('articles.edit', $article) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                    <span class="text-gray-300">|</span>
                                                @endif

                                                {{-- Tombol Delete --}}
                                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No articles found. Start writing!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>