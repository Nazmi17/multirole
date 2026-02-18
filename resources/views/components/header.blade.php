@php
    $navItems = [
        ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Catalog', 'route' => 'catalog'],
        ['name' => 'Gallery', 'route' => 'public.gallery.index'],
        ['name' => 'E-Books', 'route' => 'public.ebooks.index'],
        ['name' => 'Articles', 'route' => 'public.articles.index'],
        ['name' => 'Map', 'route' => 'map'],
        ['name' => 'Team', 'route' => 'team'],
    ];
@endphp

<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-md z-50">
    <div class="max-w-[100rem] mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            
            {{-- LOGO --}}
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

            {{-- DESKTOP MENU --}}
            <nav class="hidden lg:flex items-center gap-8">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="font-paragraph font-bold transition duration-300 {{ request()->routeIs($item['route']) ? 'text-secondary' : 'text-foreground hover:text-secondary' }}">
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </nav>

            {{-- AUTH BUTTONS (DESKTOP WITH DROPDOWN) --}}
            <div class="hidden lg:flex items-center gap-4">
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-3">
                        @auth
                            {{-- DROPDOWN MENU --}}
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="font-bold py-2 px-4 rounded-xl transition duration-300 bg-secondary text-secondary-foreground hover:bg-secondary/80 flex items-center gap-2 focus:outline-none">
                                        {{-- Tampilkan Nama User --}}
                                        <span>{{ Auth::user()->name }}</span>
                                        
                                        {{-- Icon Panah Bawah --}}
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-7.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    {{-- 1. Link Dashboard --}}
                                    @if(auth()->user()->hasRole('admin'))
                                        <x-dropdown-link :href="route('admin.dashboard')">
                                            {{ __('Admin Panel') }}
                                        </x-dropdown-link>
                                    @else
                                        <x-dropdown-link :href="route('dashboard')">
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>
                                    @endif

                                    {{-- 2. Link Profile --}}
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100"></div>

                                    {{-- 3. Link Logout --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                class="text-red-600 hover:text-red-700 hover:bg-red-50"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>

                        @else
                            {{-- GUEST BUTTONS --}}
                            <a href="{{ route('login') }}"
                               class="font-bold py-2 px-4 rounded-xl transition duration-300 text-foreground hover:bg-gray-100 border border-transparent">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="font-bold py-2 px-4 rounded-xl transition duration-300 bg-primary text-foreground hover:bg-primary/80">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition duration-300 focus:outline-none"
                    aria-label="Toggle menu"
                    type="button">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6 text-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- MOBILE MENU DROPDOWN --}}
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden overflow-hidden" 
             style="display: none;">
            
            <div class="pt-6 pb-4 space-y-2 border-t border-gray-100 mt-4">
                {{-- Search Mobile --}}
                <form action="{{ route('public.articles.index') }}" method="GET" class="relative mb-6">
                    <input type="text" name="search" placeholder="Search articles..."
                           class="font-paragraph w-full pl-10 pr-4 py-2 rounded-xl border-2 border-gray-200 focus:border-secondary focus:outline-none transition duration-300">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-foreground/40">🔍</span>
                </form>

                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block font-paragraph font-bold py-3 px-4 rounded-xl transition duration-300 {{ request()->routeIs($item['route']) ? 'bg-secondary text-secondary-foreground' : 'text-foreground hover:bg-gray-50' }}">
                        {{ $item['name'] }}
                    </a>
                @endforeach

                <div class="border-t border-gray-100 my-2 pt-2 space-y-1">
                    @auth
                        {{-- Mobile: Header User --}}
                        <div class="px-4 py-2 font-bold text-xs text-gray-400 uppercase tracking-wider">
                            {{ Auth::user()->name }}
                        </div>

                        {{-- Mobile: Dashboard Link --}}
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="block font-paragraph font-bold py-2 px-4 rounded-xl text-foreground hover:bg-gray-50">Admin Panel</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="block font-paragraph font-bold py-2 px-4 rounded-xl text-foreground hover:bg-gray-50">Dashboard</a>
                        @endif
                        
                        {{-- Mobile: Profile Link --}}
                        <a href="{{ route('profile.edit') }}" class="block font-paragraph font-bold py-2 px-4 rounded-xl text-foreground hover:bg-gray-50">Profile</a>

                        {{-- Mobile: Logout Link --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               class="block font-paragraph font-bold py-2 px-4 rounded-xl text-red-600 hover:bg-red-50"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </a>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block font-paragraph font-bold py-3 px-4 rounded-xl text-foreground hover:bg-gray-50">Log in</a>
                        <a href="{{ route('register') }}" class="block font-paragraph font-bold py-3 px-4 rounded-xl text-foreground hover:bg-gray-50">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>