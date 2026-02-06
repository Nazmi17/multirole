<nav class="flex flex-col h-full bg-white border-r border-gray-100">
    <div class="flex items-center justify-center h-16 px-4 border-b border-gray-100 shrink-0">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block w-auto h-9 text-gray-800 fill-current" />
        </a>
    </div>

    <div class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
        @if (auth()->user()->hasRole('user'))
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                {{ __('Dashboard') }}
            </x-nav-link>
        @elseif (auth()->user()->hasRole('admin'))
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.dashboard') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium transition">
                {{ __('Hak Akses') }}
            </x-nav-link>
        @endif

        @can('view users')
            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.users.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium transition">
                {{ __('User Management') }}
            </x-nav-link>
        @endcan

        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('categories.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium transition">
                {{ __('Category Management') }}
        </x-nav-link>

        <x-nav-link :href="route('galleries.index')" :active="request()->routeIs('galleries.*')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('galleries.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium transition">
                {{ __('Gallery Management') }}
        </x-nav-link>

        <x-nav-link :href="route('albums.index')" :active="request()->routeIs('albums.*')" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('albums.*') ? 'border-indigo-400 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-600 hover:bg-gray-50' }} text-base font-medium transition">
                {{ __('Album Management') }}
        </x-nav-link>
    </div>

    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center w-full mb-4">
            <div class="shrink-0 mr-3">
                <img class="object-cover w-10 h-10 rounded-full" 
                     src="{{ Auth::user()->avatar_url }}" 
                     alt="{{ Auth::user()->name }}" />
            </div>
            <div class="overflow-hidden">
                <div class="text-base font-medium text-gray-800 truncate">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>