@extends('layouts-pages.app')

@push('styles')
    <style>
        @keyframes blob {
            0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            33% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            66% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
        }
        .text-stroke {
            -webkit-text-stroke: 1px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')
    @php
        $features = [
            [
                'icon' => '🔍',
                'title' => 'Login',
                'description' => 'Login to access our exclusive member-only features and exclusive offers',
            ],
            [
                'icon' => '🍽️',
                'title' => 'Food Catalog',
                'description' => 'Browse our extensive collection of traditional Sundanese dishes with detailed descriptions',
            ],
            [
                'icon' => '🖼️',
                'title' => 'Gallery View',
                'description' => 'Explore beautiful photography of our authentic Sundanese cuisine',
            ],
            [
                'icon' => '📰',
                'title' => 'Article Section',
                'description' => 'Read stories and insights about Sundanese culinary heritage and traditions',
            ],
            [
                'icon' => '📍',
                'title' => 'Location Map',
                'description' => 'Find us easily with our integrated map showing our restaurant location',
            ],
            [
                'icon' => '👤',
                'title' => 'Team Profiles',
                'description' => 'Meet the passionate team behind Nazmi Restaurant and our culinary journey',
            ],
        ];

        $benefits = [
            ['icon' => '⏱️', 'text' => 'Fast Navigation'],
            ['icon' => '❤️', 'text' => 'Clean UI'],
            ['icon' => '🔍', 'text' => 'User-Friendly Search'],
            ['icon' => '📱', 'text' => 'Responsive Design'],
        ];

        $galleryPreview = [
            ['id' => 1, 'alt' => 'Traditional Sundanese dish presentation'],
            ['id' => 2, 'alt' => 'Authentic Indonesian cuisine'],
            ['id' => 3, 'alt' => 'Fresh ingredients and spices'],
            ['id' => 4, 'alt' => 'Traditional cooking methods'],
            ['id' => 5, 'alt' => 'Sundanese food platter'],
            ['id' => 6, 'alt' => 'Traditional serving style'],
        ];

        $columnOne = [$galleryPreview[0], $galleryPreview[3]];
        $columnTwo = [$galleryPreview[1], $galleryPreview[4]];
        $columnThree = [$galleryPreview[2], $galleryPreview[5]];
    @endphp

    <div class="min-h-screen bg-primary font-paragraph overflow-clip selection:bg-secondary selection:text-white">
        <div class="fixed top-0 left-0 right-0 h-2 bg-secondary origin-left z-50"></div>

        <main class="relative w-full">
            {{-- HERO SECTION --}}
            <section class="relative min-h-[100vh] flex items-center justify-center pt-20 overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute top-0 right-0 w-2/3 h-full bg-white/40 rounded-l-[100px] transform translate-x-1/4 skew-x-[-10deg]"></div>
                    <div class="absolute bottom-20 left-10 w-64 h-64 bg-accent-muted-red/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-40 right-20 w-96 h-96 bg-secondary/10 rounded-full blur-3xl"></div>
                </div>

                <div class="w-full max-w-[120rem] mx-auto px-6 md:px-12 relative z-10">
                    <div class="grid lg:grid-cols-12 gap-12 items-center">
                        <div class="lg:col-span-7 flex flex-col gap-8">
                            <div>
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/60 backdrop-blur-sm border border-secondary/20 text-secondary-foreground/80 mb-6">
                                    <span class="w-4 h-4 text-secondary">🌿</span>
                                    <span class="text-sm font-bold tracking-wide uppercase text-foreground">Authentic Sundanese Flavors</span>
                                </div>

                                <h1 class="font-heading text-6xl md:text-7xl lg:text-8xl xl:text-9xl text-foreground leading-[0.9] tracking-tight mb-6">
                                    Taste the <br />
                                    <span class="text-secondary italic relative inline-block">
                                        Tradition
                                        <svg class="absolute w-full h-4 -bottom-2 left-0 text-accent-muted-red opacity-60" viewBox="0 0 100 10" preserveAspectRatio="none">
                                            <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="3" fill="none" />
                                        </svg>
                                    </span>
                                </h1>

                                <p class="font-paragraph text-xl md:text-2xl text-foreground/70 max-w-2xl leading-relaxed">
                                    Welcome to Nazmi Restaurant. A culinary journey where heritage meets modern dining. Experience the warmth of Indonesia on every plate.
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-4">
                                <a
                                    href="{{ route('public.articles.index') }}"
                                    class="group px-8 py-4 bg-white text-foreground border-2 border-transparent hover:border-secondary/30 rounded-2xl font-bold text-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2"
                                >
                                    View Gallery <span class="w-5 h-5 group-hover:translate-x-1 transition-transform">📰</span>
                                </a>
                                <a
                                    href="{{ route('gallery') }}"
                                    class="group px-8 py-4 bg-white text-foreground border-2 border-transparent hover:border-secondary/30 rounded-2xl font-bold text-lg shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-2"
                                >
                                    View Gallery <span class="w-5 h-5 group-hover:translate-x-1 transition-transform">🖼️</span>
                                </a>
                            </div>
                        </div>

                        <div class="lg:col-span-5 relative h-[60vh] lg:h-[80vh] flex items-center justify-center">
                            <div class="relative w-full h-full max-h-[800px]">
                                <div class="relative w-full h-full">
                                    <div class="absolute inset-0 bg-secondary/20 rounded-[40%_60%_70%_30%/40%_50%_60%_50%] animate-[blob_7s_infinite] blur-2xl scale-110"></div>
                                    <div class="relative overflow-hidden rounded-[40%_60%_70%_30%/40%_50%_60%_50%] shadow-2xl transform transition-transform hover:scale-[1.02] duration-500">
                                        <img
                                            src="https://static.wixstatic.com/media/dea205_57a1e2ab110b4666b5e848f83a2eac82~mv2.png?originWidth=960&originHeight=768"
                                            alt="Signature Sundanese Dish"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                </div>

                                <div class="absolute top-10 -right-4 md:-right-10 bg-white p-4 rounded-2xl shadow-xl z-20 max-w-[200px]">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="p-2 bg-accent-muted-red/10 rounded-full text-accent-muted-red">
                                            <span class="w-5 h-5">👨‍🍳</span>
                                        </div>
                                        <span class="font-bold text-sm text-foreground">Chef's Choice</span>
                                    </div>
                                    <p class="text-xs text-foreground/70">Hand-picked ingredients from local farmers.</p>
                                </div>
{{-- 
                                <div class="absolute bottom-20 -left-4 md:-left-10 bg-white p-4 rounded-2xl shadow-xl z-20 flex items-center gap-3">
                                    <div class="bg-secondary/20 p-3 rounded-full">
                                        <span class="w-6 h-6 text-secondary">🌿</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-foreground">Fresh & Local</p>
                                        <p class="text-xs text-foreground/60">Daily sourced</p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-foreground/40">
                    <span class="text-xs uppercase tracking-widest">Scroll</span>
                    <div class="w-px h-12 bg-gradient-to-b from-foreground/40 to-transparent"></div>
                </div> --}}
            </section>

            {{-- ABOUT SECTION --}}
            <section class="relative bg-white" id="about">
                <x-section-divider />
                <div class="w-full max-w-[120rem] mx-auto px-6 md:px-12 py-24 md:py-32">
                    <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
                        <div class="lg:w-1/2 relative">
                            <div class="sticky top-32 h-[60vh] min-h-[500px] w-full rounded-[3rem] overflow-hidden shadow-2xl">
                                <img
                                    src="https://static.wixstatic.com/media/dea205_6c0b7520e79443a193a8f98a77f057d1~mv2.png?originWidth=1152&originHeight=576"
                                    alt="Restaurant Interior and Atmosphere"
                                    class="w-full h-full object-cover"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex flex-col justify-end p-10">
                                    <p class="text-white/90 font-heading text-3xl">"Where every meal tells a story."</p>
                                </div>
                            </div>
                        </div>

                        <div class="lg:w-1/2 flex flex-col justify-center py-10 lg:py-20">
                            <span class="text-accent-muted-red font-bold tracking-wider uppercase mb-4 block">Our Heritage</span>
                            <h2 class="font-heading text-5xl md:text-6xl text-foreground mb-8 leading-tight">
                                Preserving the <br />
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary to-secondary/70">
                                    Soul of Sunda
                                </span>
                            </h2>

                            <div class="space-y-8 text-lg text-foreground/70 leading-relaxed">
                                <p>
                                    Nazmi Restaurant is more than just a place to eat; it is a sanctuary for the rich culinary traditions of West Java. Our journey began with a simple passion: to keep the authentic flavors of Sundanese cuisine alive in a rapidly modernizing world.
                                </p>
                                <p>
                                    Every recipe has been passed down through generations, ensuring that the taste you experience is as genuine as the meals served in a traditional Sundanese home. From the spicy kick of our Sambal to the comforting warmth of our Sayur Asem, we invite you to taste the history.
                                </p>

                                <div class="p-8 bg-primary rounded-3xl border border-secondary/10">
                                    <h3 class="font-heading text-2xl text-foreground mb-4 flex items-center gap-3">
                                        <span class="w-6 h-6 text-secondary">⭐</span>
                                        School Project Initiative
                                    </h3>
                                    <p class="text-base">
                                        This website serves as a digital showcase of our restaurant's features and offerings, created as part of a school project to demonstrate modern web design principles while honoring traditional food culture.
                                    </p>
                                </div>

                                <div class="pt-4">
                                    <a
                                        href="{{ route('team') }}"
                                        class="inline-flex items-center gap-3 text-foreground font-bold text-xl hover:text-secondary transition-colors group"
                                    >
                                        Meet Our Team
                                        <span class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all">
                                            <span class="w-5 h-5">➡️</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- FEATURES SECTION --}}
            <section class="relative py-32 bg-primary overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                    <div class="absolute top-1/4 -right-64 w-[500px] h-[500px] bg-secondary/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-1/4 -left-64 w-[500px] h-[500px] bg-accent-muted-red/5 rounded-full blur-3xl"></div>
                </div>

                <div class="w-full max-w-[120rem] mx-auto px-6 md:px-12 relative z-10">
                    <div class="text-center max-w-3xl mx-auto mb-20">
                        <h2 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                            Digital Experience
                        </h2>
                        <p class="text-xl text-foreground/70">
                            Explore the comprehensive features built to enhance your culinary journey.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($features as $feature)
                            <div class="group relative bg-white rounded-[2.5rem] p-10 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-primary rounded-bl-[100px] -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-700 ease-in-out"></div>

                                <div class="relative z-10">
                                    <div class="w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-secondary group-hover:text-white transition-colors duration-300">
                                        <span class="text-secondary group-hover:text-white text-2xl">{{ $feature['icon'] }}</span>
                                    </div>

                                    <h3 class="font-heading text-2xl font-bold text-foreground mb-4 group-hover:text-secondary transition-colors">
                                        {{ $feature['title'] }}
                                    </h3>

                                    <p class="text-foreground/60 leading-relaxed mb-6">
                                        {{ $feature['description'] }}
                                    </p>

                                    <div class="w-8 h-8 rounded-full border border-foreground/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-x-[-10px] group-hover:translate-x-0 transition-all duration-300">
                                        <span class="w-4 h-4 text-foreground">➡️</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- 
                VISUAL FEAST SECTION (FIXED)
                PERBAIKAN: Mengganti bg-foreground menjadi bg-neutral-900.
                Ini adalah section gelap, jadi kita harus memastikan backgroundnya gelap agar teks putih terbaca.
            --}}
            
        </main>
    </div>
@endsection