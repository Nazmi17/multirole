
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Two Factor Authentication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if($enabled)
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-green-600">✅ 2FA Sudah Aktif</h3>
                        <p class="mb-4">Akun Anda aman. Anda perlu memasukkan kode dari Google Authenticator saat login.</p>
                        
                        <form method="POST" action="{{ route('2fa.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Matikan 2FA</x-danger-button>
                        </form>
                    </div>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Aktifkan Google Authenticator</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="mb-2">1. Scan QR Code ini dengan aplikasi Google Authenticator:</p>
                            <div class="mb-4">
                                {!! $QR_Image !!}
                            </div>
                            <p class="text-sm text-gray-500">Secret Key: {{ $secret }}</p>
                        </div>

                        <div>
                            <p class="mb-2">2. Masukkan kode OTP yang muncul di aplikasi:</p>
                            <form method="POST" action="{{ route('2fa.store') }}">
                                @csrf
                                <input type="hidden" name="secret" value="{{ $secret }}">
                                
                                <div class="mb-4">
                                    <x-input-label for="otp" :value="__('Kode OTP')" />
                                    <x-text-input id="otp" name="otp" type="text" class="mt-1 block w-full" required autofocus />
                                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                                </div>

                                <x-primary-button>Aktifkan</x-primary-button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>