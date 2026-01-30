<head>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="login" :value="__('Email/Username')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-4 flex flex-col justify-center"> 
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
            
            {{-- Menampilkan Error jika user lupa centang --}}
            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4 gap-4">
            
            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                    {{ __('Register') }}
                </a>
            @endif

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="flex flex-col items-center mt-6">
        <div class="flex justify-center w-full mb-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink-0 mx-4 text-gray-500 text-sm">Or Login With</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition font-semibold">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z" fill="#FFFFFF"/>
                    <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.35 21.48 7.42 24 12 24z" fill="#FFFFFF"/>
                    <path d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29C.47 8.24 0 10.06 0 12c0 1.94.47 3.76 1.29 5.38l3.98-3.09z" fill="#FFFFFF"/>
                    <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.42 0 3.35 2.52 1.29 6.62l3.98 3.09c.95-2.85 3.6-4.96 6.73-4.96z" fill="#FFFFFF"/>
                </svg>
                Google
            </a>
        </div>
    </div>
</x-guest-layout>