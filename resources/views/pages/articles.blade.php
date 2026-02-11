@extends('layouts-pages.app')

@section('content')
    @php
        $categories = ['All', 'Recipes', 'Culture', 'News', 'Tips'];
        $articles = [
            [
                'title' => 'Sundanese Flavors Through the Ages',
                'category' => 'Culture',
                'author' => 'Nazmi Team',
                'date' => 'Oct 10, 2026',
                'readTime' => '6 min read',
                'excerpt' => 'Explore how Sundanese culinary traditions have been preserved across generations.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
            [
                'title' => 'Five Signature Recipes to Try at Home',
                'category' => 'Recipes',
                'author' => 'Chef Dimas',
                'date' => 'Sep 28, 2026',
                'readTime' => '8 min read',
                'excerpt' => 'Discover the steps behind our most-loved dishes with easy-to-follow guidance.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
            [
                'title' => 'Community Events at Nazmi Restaurant',
                'category' => 'News',
                'author' => 'Nazmi Team',
                'date' => 'Sep 14, 2026',
                'readTime' => '4 min read',
                'excerpt' => 'Highlights from recent gatherings celebrating Sundanese culture and cuisine.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
            [
                'title' => 'Tips for Pairing Traditional Drinks',
                'category' => 'Tips',
                'author' => 'Nazmi Team',
                'date' => 'Aug 30, 2026',
                'readTime' => '5 min read',
                'excerpt' => 'A guide to complementing your meal with refreshing Sundanese beverages.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
            [
                'title' => 'The Story Behind Our Sambal',
                'category' => 'Culture',
                'author' => 'Chef Lina',
                'date' => 'Aug 12, 2026',
                'readTime' => '7 min read',
                'excerpt' => 'Learn how our signature sambal blends heat, aroma, and heritage.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
            [
                'title' => 'Seasonal Ingredients We Love',
                'category' => 'News',
                'author' => 'Nazmi Team',
                'date' => 'Jul 29, 2026',
                'readTime' => '3 min read',
                'excerpt' => 'Fresh produce and herbs that shape our rotating menu highlights.',
                'image' => 'https://static.wixstatic.com/media/dea205_67e727fc7640448c86b3933d33d3a59c~mv2.png?originWidth=448&originHeight=256',
            ],
        ];
    @endphp

    <div class="min-h-screen bg-background">
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Articles & Stories
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Discover the rich heritage of Sundanese cuisine through our collection of stories, recipes, and cultural insights.
                </p>
            </div>
        </section>

        <section class="w-full py-8 bg-white shadow-sm sticky top-[88px] z-40">
            <div class="max-w-[100rem] mx-auto px-6">
                {{-- TODO: replace with Laravel backend logic for filtering. --}}
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach ($categories as $category)
                        <button
                            type="button"
                            class="font-paragraph font-bold py-2 px-6 rounded-xl transition duration-300 bg-primary text-foreground hover:bg-secondary/20"
                        >
                            {{ $category }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full py-16 md:py-24 min-h-[600px]">
            <div class="max-w-[100rem] mx-auto px-6">
                {{-- TODO: replace with Laravel backend logic to load articles. --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($articles as $article)
                        <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:scale-105 transition duration-300">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <img
                                    src="{{ $article['image'] }}"
                                    alt="{{ $article['title'] }}"
                                    class="w-full h-full object-cover"
                                />
                                <div class="absolute top-4 left-4 bg-secondary text-secondary-foreground font-paragraph font-bold text-sm py-2 px-4 rounded-xl shadow-md">
                                    {{ $article['category'] }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h2 class="font-heading text-2xl text-foreground mb-3 line-clamp-2">
                                    {{ $article['title'] }}
                                </h2>
                                <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-3 leading-relaxed">
                                    {{ $article['excerpt'] }}
                                </p>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-foreground/60">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4">👤</span>
                                        <span class="font-paragraph">{{ $article['author'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4">📅</span>
                                        <span class="font-paragraph">{{ $article['date'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4">⏱️</span>
                                        <span class="font-paragraph">{{ $article['readTime'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <button
                        type="button"
                        class="bg-secondary text-secondary-foreground font-paragraph font-bold py-3 px-8 rounded-xl shadow-lg hover:scale-105 hover:shadow-xl transition duration-300 ease-in-out"
                    >
                        Load More Articles
                    </button>
                </div>
            </div>
        </section>
    </div>
@endsection