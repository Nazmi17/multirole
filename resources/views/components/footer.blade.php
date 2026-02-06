@php
    $navigationLinks = [
        ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Catalog', 'route' => 'catalog'],
        ['name' => 'Gallery', 'route' => 'gallery'],
        ['name' => 'Articles', 'route' => 'articles'],
        ['name' => 'Map', 'route' => 'map'],
        ['name' => 'Profile', 'route' => 'profile'],
    ];

    $socialLinks = [
        ['label' => 'Facebook', 'href' => '#', 'icon' => '📘'],
        ['label' => 'Instagram', 'href' => '#', 'icon' => '📸'],
        ['label' => 'Twitter', 'href' => '#', 'icon' => '🐦'],
    ];
@endphp

<footer class="bg-foreground text-white">
    <div class="max-w-[100rem] mx-auto px-6 py-16">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-secondary rounded-2xl w-12 h-12 flex items-center justify-center shadow-md">
                        <span class="font-heading text-2xl text-secondary-foreground font-bold">N</span>
                    </div>
                    <div>
                        <h3 class="font-heading text-xl font-bold">Nazmi Restaurant</h3>
                        <p class="font-paragraph text-sm text-white/70">Sundanese Cuisine</p>
                    </div>
                </div>
                <p class="font-paragraph text-white/80 leading-relaxed mb-6">
                    Celebrating the rich traditions of Sundanese culinary heritage with authentic flavors and warm hospitality.
                </p>
                <div class="flex gap-4">
                    @foreach ($socialLinks as $social)
                        <a
                            href="{{ $social['href'] }}"
                            aria-label="{{ $social['label'] }}"
                            class="bg-white/10 rounded-xl w-10 h-10 flex items-center justify-center hover:bg-secondary hover:scale-110 transition duration-300"
                        >
                            <span class="text-lg">{{ $social['icon'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-heading text-lg font-bold mb-6">Quick Links</h4>
                <ul class="space-y-3">
                    @foreach ($navigationLinks as $link)
                        <li>
                            <a
                                href="{{ route($link['route']) }}"
                                class="font-paragraph text-white/80 hover:text-secondary transition duration-300"
                            >
                                {{ $link['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="font-heading text-lg font-bold mb-6">Contact Us</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 text-secondary mt-1 flex-shrink-0">📍</span>
                        <span class="font-paragraph text-white/80">
                            Jl. Sundanese Heritage No. 123<br />
                            Bandung, West Java, Indonesia
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 text-secondary flex-shrink-0">📞</span>
                        <span class="font-paragraph text-white/80">
                            +62 812-3456-7890
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-5 h-5 text-secondary flex-shrink-0">✉️</span>
                        <span class="font-paragraph text-white/80">
                            info@nazmirestaurant.com
                        </span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-heading text-lg font-bold mb-6">Opening Hours</h4>
                <ul class="space-y-3 font-paragraph text-white/80">
                    <li class="flex justify-between">
                        <span>Monday - Friday</span>
                        <span class="font-bold">10:00 - 22:00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Saturday</span>
                        <span class="font-bold">09:00 - 23:00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sunday</span>
                        <span class="font-bold">09:00 - 22:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/20 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-paragraph text-white/70 text-sm text-center md:text-left">
                    © 2026 Nazmi Restaurant. All rights reserved.
                </p>
                <div class="bg-accent-muted-red/20 border border-accent-muted-red/40 rounded-xl px-6 py-3">
                    <p class="font-paragraph text-white/90 text-sm font-bold text-center">
                        📚 This website is a school project
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
