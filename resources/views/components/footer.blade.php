@php
    $navigationLinks = [
       ['name' => 'Home', 'route' => 'home'],
        ['name' => 'Catalog', 'route' => 'catalog'],
        ['name' => 'Gallery', 'route' => 'gallery'],
        ['name' => 'Articles', 'route' => 'public.articles.index'],
        ['name' => 'Map', 'route' => 'map'],
        ['name' => 'Team', 'route' => 'team'],
    ];

    $socialLinks = [
        ['label' => 'Facebook', 'href' => '#', 'icon' => '📘'],
        ['label' => 'Instagram', 'href' => '#', 'icon' => '📸'],
        ['label' => 'Twitter', 'href' => '#', 'icon' => '🐦'],
    ];
@endphp

{{-- 
    PERBAIKAN: 
    Mengganti 'bg-foreground' menjadi 'bg-neutral-900' (atau bisa pakai bg-slate-900 / bg-black).
    Ini memastikan background menjadi gelap sehingga 'text-white' terlihat jelas.
--}}
<footer class="bg-neutral-900 text-white font-paragraph">
    <div class="max-w-[100rem] mx-auto px-6 py-16">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            {{-- Kolom 1: Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-secondary rounded-2xl w-12 h-12 flex items-center justify-center shadow-md">
                        <span class="font-heading text-2xl text-secondary-foreground font-bold">N</span>
                    </div>
                    <div>
                        <h3 class="font-heading text-xl font-bold">Nazmi Restaurant</h3>
                        <p class="text-sm text-gray-400">Masakan Sunda</p>
                    </div>
                </div>
                <p class="text-gray-300 leading-relaxed mb-6">
                    Merayakan kekayaan tradisi kuliner Sunda dengan rasa yang autentik dan hospitalitas yang hangat.
                </p>
                <div class="flex gap-4">
                    @foreach ($socialLinks as $social)
                        <a
                            href="{{ $social['href'] }}"
                            aria-label="{{ $social['label'] }}"
                            class="bg-white/10 rounded-xl w-10 h-10 flex items-center justify-center hover:bg-secondary hover:text-secondary-foreground hover:scale-110 transition duration-300"
                        >
                            <span class="text-lg">{{ $social['icon'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Kolom 2: Link Cepat --}}
            <div>
                <h4 class="font-heading text-lg font-bold mb-6 text-white">Link Cepat</h4>
                <ul class="space-y-3">
                    @foreach ($navigationLinks as $link)
                        <li>
                            <a
                                href="{{ route($link['route']) }}"
                                class="text-gray-400 hover:text-secondary transition duration-300 inline-block hover:translate-x-1"
                            >
                                {{ $link['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div>
                <h4 class="font-heading text-lg font-bold mb-6 text-white">Hubungi Kami</h4>
                <ul class="space-y-4 text-gray-300">
                    <li class="flex items-start gap-3">
                        <span class="text-secondary mt-1 flex-shrink-0">📍</span>
                        <span>
                            Jl. Sundanese Heritage No. 123<br />
                            Bandung, Jawa Barat, Indonesia
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-secondary flex-shrink-0">📞</span>
                        <span>
                            +62 812-3456-7890
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="text-secondary flex-shrink-0">✉️</span>
                        <span>
                            info@nazmirestaurant.com
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 4: Jam Operasional --}}
            <div>
                <h4 class="font-heading text-lg font-bold mb-6 text-white">Jam Operasional</h4>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex justify-between border-b border-white/10 pb-2">
                        <span>Senin - Jumat</span>
                        <span class="font-bold text-white">10:00 - 22:00</span>
                    </li>
                    <li class="flex justify-between border-b border-white/10 pb-2">
                        <span>Sabtu</span>
                        <span class="font-bold text-white">09:00 - 23:00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Minggu</span>
                        <span class="font-bold text-white">09:00 - 22:00</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="border-t border-white/10 pt-8 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm text-center md:text-left">
                    © 2026 Nazmi Restaurant. Hak cipta dilindungi.
                </p>
                <div class="bg-white/5 border border-white/10 rounded-xl px-6 py-3 backdrop-blur-sm">
                    <p class="text-gray-300 text-sm font-bold text-center flex items-center gap-2">
                        <span>📚</span> Website ini adalah proyek sekolah
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>