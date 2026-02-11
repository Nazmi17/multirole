@php
    $navItems = [
        ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Catalog', 'route' => 'catalog'],
        ['name' => 'Gallery', 'route' => 'gallery'],
        ['name' => 'Articles', 'route' => 'articles'],
        ['name' => 'Map', 'route' => 'map'],
        ['name' => 'Profile', 'route' => 'profile'],
    ];
@endphp

<header class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-md z-50">
    <div class="max-w-[100rem] mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="bg-secondary rounded-2xl w-12 h-12 flex items-center justify-center shadow-md group-hover:scale-110 transition duration-300">
                    <span class="font-heading text-2xl text-secondary-foreground font-bold">N</span>
                </div>
                <div>
                    <h1 class="font-heading text-xl md:text-2xl text-foreground font-bold">
                        Nazmi Restaurant
                    </h1>
                    <p class="font-paragraph text-xs text-foreground/60 hidden sm:block">
                        Authentic Sundanese Cuisine
                    </p>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-8">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="font-paragraph font-bold transition duration-300 {{ request()->routeIs($item['route']) ? 'text-secondary' : 'text-foreground hover:text-secondary' }}"
                    >
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-4">
                {{-- TODO: replace with Laravel backend logic for search. --}}
                 @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-block px-5 py-1.5 dark:text-black  border-[#19140035] text-black border hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 dark:text-black border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>

            <button
                class="lg:hidden p-2 rounded-xl hover:bg-primary transition duration-300"
                aria-label="Toggle menu"
                type="button"
            >
                <span class="w-6 h-6 text-foreground">☰</span>
            </button>
        </div>

        <div class="lg:hidden overflow-hidden">
            {{-- TODO: replace with mobile menu toggle logic. --}}
            <div class="pt-6 pb-4 space-y-4">
                <div class="relative mb-4">
                    <input
                        type="text"
                        placeholder="Search..."
                        class="font-paragraph w-full pl-10 pr-4 py-2 rounded-xl border-2 border-neutral-gray focus:border-secondary focus:outline-none transition duration-300"
                    />
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-foreground/40">🔍</span>
                </div>

                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="block font-paragraph font-bold py-2 px-4 rounded-xl transition duration-300 {{ request()->routeIs($item['route']) ? 'bg-secondary text-secondary-foreground' : 'text-foreground hover:bg-primary' }}"
                    >
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>
