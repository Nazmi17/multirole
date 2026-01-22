<x-guest-layout>
    <form method="POST" action="{{ route('register') }}"
        x-data="{ 
            password: '', 
            password_confirmation: '',
            requirements: [
                { regex: /.{8,}/, text: 'Minimal 8 karakter' },
                { regex: /[A-Z]/, text: 'Harus ada huruf besar' },
                { regex: /[0-9]/, text: 'Harus ada angka' },
                { regex: /[^A-Za-z0-9]/, text: 'Harus ada simbol (!@#$%^&*)' }
            ],
            get strength() {
                return this.requirements.filter(req => req.regex.test(this.password)).length;
            },
            get strengthLabel() {
                if (this.strength <= 1) return 'Lemah';
                if (this.strength <= 3) return 'Sedang';
                return 'Kuat';
            },
            get barColor() {
                if (this.strength <= 1) return 'bg-red-500';
                if (this.strength <= 3) return 'bg-yellow-500';
                return 'bg-green-500';
            },
            get isComplete() {
                // Wajib diisi dan Valid
                return this.strength === 4 && this.password === this.password_confirmation;
            }
        }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            x-model="password" 
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="mt-2" x-show="password.length > 0" x-transition>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold text-gray-700" x-text="strengthLabel"></span>
                    <span class="text-xs text-gray-500" x-text="(strength / 4 * 100) + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mb-3">
                    <div class="h-1.5 rounded-full transition-all duration-300" 
                        :class="barColor" 
                        :style="'width: ' + (strength / 4 * 100) + '%'"></div>
                </div>
                <ul class="text-xs space-y-1">
                    <template x-for="(req, index) in requirements" :key="index">
                        <li class="flex items-center" 
                            :class="req.regex.test(password) ? 'text-green-600 font-medium' : 'text-gray-500'">
                            <span class="mr-2" x-text="req.regex.test(password) ? '✓' : '○'"></span>
                            <span x-text="req.text"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" 
                            x-model="password_confirmation"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
             
            <p x-show="password.length > 0 && password_confirmation.length > 0 && password !== password_confirmation" 
               class="text-red-500 text-xs mt-1">
               Password tidak sama!
            </p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" 
                ::disabled="!isComplete"
                ::class="!isComplete ? 'opacity-50 cursor-not-allowed' : ''">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
