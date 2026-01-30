<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ini adalah area aman. Harap konfirmasi password Anda atau gunakan Google Authenticator sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- OPSI 1: PASSWORD (Selalu Ada) --}}
        <div>
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            {{-- Password tidak required jika user isi OTP, tapi kita handle di backend --}}
                            autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- OPSI 2: GOOGLE AUTHENTICATOR (Hanya jika user sudah aktifkan) --}}
        @if($userHas2FA)
            <div class="mt-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">ATAU gunakan kode OTP</span>
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="otp" value="{{ __('Kode Google Authenticator') }}" />
                    <x-text-input id="otp" class="block mt-1 w-full"
                                    type="text"
                                    name="otp"
                                    placeholder="123456"
                                    autocomplete="one-time-code" />
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                </div>
            </div>
        @endif

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Konfirmasi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>