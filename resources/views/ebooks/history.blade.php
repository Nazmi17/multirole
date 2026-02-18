<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('History Download') }}: <span class="text-indigo-600">{{ $ebook->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Statistik Ringkas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-gray-500 text-sm">Total Download</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $ebook->downloads()->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-gray-500 text-sm">Download Minggu Ini</div>
                    <div class="text-2xl font-bold text-indigo-600">
                        {{ $ebook->downloads()->where('downloaded_at', '>=', now()->subWeek())->count() }}
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <div class="text-gray-500 text-sm">Status E-Book</div>
                    <div class="mt-1">
                        @if($ebook->is_login_required)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                🔒 Member Only
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                🌍 Public
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tabel History --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    @if($downloads->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Download</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($downloads as $index => $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $downloads->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                {{-- Avatar (Opsional, pakai placeholder jika tidak ada) --}}
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-3">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $log->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($log->downloaded_at)->translatedFormat('d F Y, H:i') }}
                                            <span class="text-xs text-gray-400 ml-1">
                                                ({{ \Carbon\Carbon::parse($log->downloaded_at)->diffForHumans() }})
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $downloads->links() }}
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>Belum ada riwayat download untuk e-book ini.</p>
                            @if(!$ebook->is_login_required)
                                <p class="text-xs mt-1 text-orange-500">(Karena e-book ini bersifat Public, download tidak dicatat)</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>