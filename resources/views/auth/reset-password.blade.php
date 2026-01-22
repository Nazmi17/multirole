<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ 
            password: '', 
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
            }
        }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full" 
                                type="password" 
                                name="password" 
                                x-model="password" 
                                required autocomplete="new-password" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="mt-2 p-3 bg-gray-50 rounded-md border border-gray-200" x-show="password.length > 0" x-transition>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold text-gray-700" x-text="strengthLabel"></span>
                    <div class="h-1.5 w-24 bg-gray-200 rounded-full">
                        <div class="h-1.5 rounded-full transition-all duration-300" 
                                :class="barColor" 
                                :style="'width: ' + (strength / 4 * 100) + '%'"></div>
                    </div>
                </div>

                <ul class="text-xs space-y-1 mt-2">
                    <template x-for="(req, index) in requirements" :key="index">
                        <li class="flex items-center" :class="req.regex.test(password) ? 'text-green-600' : 'text-gray-400'">
                            <span class="w-2 h-2 rounded-full mr-2" :class="req.regex.test(password) ? 'bg-green-500' : 'bg-gray-300'"></span>
                            <span x-text="req.text"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
