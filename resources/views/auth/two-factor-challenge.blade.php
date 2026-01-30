<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ini adalah area yang dilindungi. Masukkan kode dari aplikasi Google Authenticator Anda untuk melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" :value="__('Authentication Code')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus autocomplete="one-time-code" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-4">
                {{ __('Verifikasi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>