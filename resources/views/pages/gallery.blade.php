@extends('layouts-pages.app')

@section('content')
    @php
        $categories = ['All', 'Food', 'Interior', 'Events', 'Team'];
        $images = [
            [
                'caption' => 'Signature Plates',
                'category' => 'Food',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
            [
                'caption' => 'Warm Dining Room',
                'category' => 'Interior',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
            [
                'caption' => 'Family Gathering',
                'category' => 'Events',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
            [
                'caption' => 'Kitchen Team',
                'category' => 'Team',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
            [
                'caption' => 'Chef Specials',
                'category' => 'Food',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
            [
                'caption' => 'Celebration Night',
                'category' => 'Events',
                'image' => 'https://static.wixstatic.com/media/dea205_3af0284ea5804928981fb06ac3d2fa74~mv2.png?originWidth=448&originHeight=448',
            ],
        ];
    @endphp

    <div class="min-h-screen bg-background">
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Our Gallery
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    A visual journey through the flavors, ambiance, and moments that make Nazmi Restaurant special.
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
                {{-- TODO: replace with Laravel backend logic to load gallery images. --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($images as $image)
                        <div class="relative group rounded-2xl overflow-hidden shadow-lg aspect-square">
                            <img
                                src="{{ $image['image'] }}"
                                alt="{{ $image['caption'] }}"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-foreground/90 via-foreground/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                <p class="font-paragraph text-white text-lg font-bold mb-2">
                                    {{ $image['caption'] }}
                                </p>
                                <p class="font-paragraph text-secondary text-sm font-bold">
                                    {{ $image['category'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <button
                        type="button"
                        class="bg-secondary text-secondary-foreground font-paragraph font-bold py-3 px-8 rounded-xl shadow-lg hover:scale-105 hover:shadow-xl transition duration-300 ease-in-out"
                    >
                        Load More Images
                    </button>
                </div>
            </div>
        </section>
    </div>
@endsection
