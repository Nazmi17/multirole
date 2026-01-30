<x-guest-layout>
    <form method="POST" action="{{ route('register') }}"
        x-data="{ 
            // 1. Tambahkan username dengan default value dari old input (jika ada error server)
            username: '{{ old('username') }}',
            password: '', 
            password_confirmation: '',
            
            requirements: [
                { id: 1, regex: /.{8,}/, text: 'Minimal 8 karakter' },
                { id: 2, regex: /[A-Z]/, text: 'Harus ada huruf besar' },
                { id: 3, regex: /[0-9]/, text: 'Harus ada angka' },
                { id: 4, regex: /[^A-Za-z0-9]/, text: 'Harus ada simbol (!@#$%^&*)' }
            ],

            // 2. Logic Validasi Username
            get usernameError() {
                if (/\s/.test(this.username)) return 'Username tidak boleh mengandung spasi';
                if (/[A-Z]/.test(this.username)) return 'Username harus huruf kecil semua';
                return null;
            },

            get sortedRequirements() {
                return this.requirements.map(req => {
                    return { ...req, met: req.regex.test(this.password) };
                }).sort((a, b) => {
                    if (a.met && !b.met) return -1;
                    if (!a.met && b.met) return 1;
                    return a.id - b.id;
                });
            },

            get isComplete() {
                // 3. Tambahkan cek !this.usernameError di sini
                return this.requirements.every(req => req.regex.test(this.password)) && 
                       this.password === this.password_confirmation &&
                       this.username.length > 0 &&
                       !this.usernameError;
            }
        }">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            
            <x-text-input id="username" class="block mt-1 w-full" 
                type="text" 
                name="username" 
                x-model="username"
                x-on:input="username = username.toLowerCase().replace(/\s/g, '')"
                required />

            <x-input-error :messages="$errors->get('username')" class="mt-2" />

            <p x-show="username.length > 0 && usernameError" 
               x-text="usernameError"
               class="text-red-500 text-xs mt-1"
               x-transition></p>
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            x-model="password" 
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="mt-2" x-show="password.length > 0" x-transition>
                <ul class="text-xs space-y-1">
                    <template x-for="req in sortedRequirements" :key="req.id">
                        <li class="flex items-center transition-all duration-500 ease-in-out transform" 
                            :class="req.met ? 'text-green-600 font-bold translate-x-1' : 'text-gray-500'">
                            <span class="mr-2 inline-block w-4 text-center transition-all" 
                                  x-text="req.met ? '✓' : '○'"></span>
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