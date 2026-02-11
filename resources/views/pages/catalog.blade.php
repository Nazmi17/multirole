@extends('layouts-pages.app')

@section('content')
    @php
        $categories = ['All', 'Main Course', 'Appetizer', 'Dessert', 'Beverage'];
        $dishes = [
            [
                'name' => 'Nasi Timbel Komplit',
                'category' => 'Main Course',
                'description' => 'Steamed rice wrapped in banana leaves served with traditional Sundanese sides.',
                'time' => 30,
                'price' => 35000,
                'vegetarian' => false,
                'image' => 'https://static.wixstatic.com/media/dea205_bb105766409e473abbacc15a0f11feb2~mv2.png?originWidth=384&originHeight=256',
            ],
            [
                'name' => 'Karedok Segar',
                'category' => 'Appetizer',
                'description' => 'Fresh vegetable salad with peanut dressing and aromatic herbs.',
                'time' => 15,
                'price' => 22000,
                'vegetarian' => true,
                'image' => 'https://static.wixstatic.com/media/dea205_bb105766409e473abbacc15a0f11feb2~mv2.png?originWidth=384&originHeight=256',
            ],
            [
                'name' => 'Es Cendol',
                'category' => 'Beverage',
                'description' => 'Sweet iced dessert drink with palm sugar, coconut milk, and pandan jelly.',
                'time' => 10,
                'price' => 15000,
                'vegetarian' => true,
                'image' => 'https://static.wixstatic.com/media/dea205_bb105766409e473abbacc15a0f11feb2~mv2.png?originWidth=384&originHeight=256',
            ],
            [
                'name' => 'Colenak',
                'category' => 'Dessert',
                'description' => 'Grilled fermented cassava topped with coconut and palm sugar.',
                'time' => 20,
                'price' => 18000,
                'vegetarian' => true,
                'image' => 'https://static.wixstatic.com/media/dea205_bb105766409e473abbacc15a0f11feb2~mv2.png?originWidth=384&originHeight=256',
            ],
        ];
    @endphp

    <div class="min-h-screen bg-background">
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Our Food Catalog
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Explore our authentic Sundanese dishes, each prepared with traditional recipes and fresh ingredients.
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
                {{-- TODO: replace with Laravel backend logic to load dishes. --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($dishes as $dish)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:scale-105 transition duration-300">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img
                                    src="{{ $dish['image'] }}"
                                    alt="{{ $dish['name'] }}"
                                    class="w-full h-full object-cover"
                                />
                                @if ($dish['vegetarian'])
                                    <div class="absolute top-3 right-3 bg-secondary rounded-full p-2 shadow-md">
                                        <span class="w-5 h-5 text-secondary-foreground">🌿</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-heading text-xl text-foreground mb-2 line-clamp-1">
                                    {{ $dish['name'] }}
                                </h3>
                                <p class="font-paragraph text-sm text-secondary font-bold mb-3">
                                    {{ $dish['category'] }}
                                </p>
                                <p class="font-paragraph text-foreground/70 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $dish['description'] }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 text-foreground/50">⏱️</span>
                                        <span class="font-paragraph text-sm text-foreground/70">
                                            {{ $dish['time'] }} min
                                        </span>
                                    </div>
                                    <p class="font-heading text-xl text-accent-muted-red font-bold">
                                        Rp {{ number_format($dish['price'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <button
                        type="button"
                        class="bg-secondary text-secondary-foreground font-paragraph font-bold py-3 px-8 rounded-xl shadow-lg hover:scale-105 hover:shadow-xl transition duration-300 ease-in-out"
                    >
                        Load More Dishes
                    </button>
                </div>
            </div>
        </section>
    </div>
@endsection
