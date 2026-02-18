<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Article: {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 prose max-w-none">
                    @if($article->featured_image)
                        <img src="{{ Storage::url($article->featured_image) }}" alt="Cover" class="w-full h-64 object-cover rounded-md mb-6">
                    @endif
                    
                    <h1 class="text-3xl font-bold mb-2">{{ $article->title }}</h1>
                    <div class="text-sm text-gray-500 mb-6 flex gap-4">
                        <span>By: {{ $article->author->name }}</span>
                        <span>Date: {{ $article->created_at->format('d M Y') }}</span>
                        <span>Categories: {{ $article->categories->pluck('title')->implode(', ') }}</span>
                    </div>

                    <hr class="my-4">

                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                    <h3 class="text-lg font-bold mb-4">Editorial Action</h3>
                    
                    <div class="mb-6">
                        <span class="block text-sm text-gray-500">Current Status:</span>
                        <span class="px-2 py-1 rounded text-sm font-bold 
                            {{ $article->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $article->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ strtoupper($article->status) }}
                        </span>
                    </div>

                    {{-- Form Approve --}}
                    <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Publish this article?')">
                            ✅ Approve & Publish
                        </button>
                    </form>

                    <hr class="my-6 border-gray-200">

                    {{-- Form Reject --}}
                    <form action="{{ route('admin.articles.reject', $article) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason (Feedback)</label>
                        <textarea id="feedback" name="admin_feedback" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm mb-2" placeholder="Explain why this article is rejected..." required></textarea>
                        
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Reject this article?')">
                            ❌ Reject & Return
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>