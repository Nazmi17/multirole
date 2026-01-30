<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Update username, nama, dan password akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" enctype="multipart/form-data">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data"
        x-data="{ 
            // Init data
            username: '{{ old('username', $user->username) }}',
            password: '', 
            password_confirmation: '',
            
            requirements: [
                { id: 1, regex: /.{8,}/, text: 'Minimal 8 karakter' },
                { id: 2, regex: /[A-Z]/, text: 'Harus ada huruf besar' },
                { id: 3, regex: /[0-9]/, text: 'Harus ada angka' },
                { id: 4, regex: /[^A-Za-z0-9]/, text: 'Harus ada simbol (!@#$%^&*)' }
            ],

            // 1. Validasi Username
            get usernameError() {
                if (/\s/.test(this.username)) return 'Username tidak boleh mengandung spasi';
                if (/[A-Z]/.test(this.username)) return 'Username harus huruf kecil semua';
                return null;
            },

            // 2. Sorting List Password
            get sortedRequirements() {
                return this.requirements.map(req => {
                    return { ...req, met: req.regex.test(this.password) };
                }).sort((a, b) => {
                    if (a.met && !b.met) return -1;
                    if (!a.met && b.met) return 1;
                    return a.id - b.id;
                });
            },

            // 3. Cek Keseluruhan Form
            get isComplete() {
                // Username wajib valid
                if (this.username.length === 0 || this.usernameError) return false;

                // Password opsional (untuk update profile)
                // Jika kosong: Valid (True)
                if (this.password === '') return true;

                // Jika diisi: Wajib memenuhi semua syarat & confirm cocok
                return this.requirements.every(req => req.regex.test(this.password)) && 
                       this.password === this.password_confirmation;
            }
        }">
        
        @csrf
        @method('patch')

        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                {{-- Tampilkan Avatar User --}}
                <img 
                    src="{{ $user->avatar_url }}" 
                    alt="{{ $user->name }}" 
                    class="rounded-full h-20 w-20 object-cover"
                >
            </div>
            <label class="block">
                <span class="sr-only">Choose profile photo</span>
                <input type="file" name="avatar" 
                    class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-violet-50 file:text-violet-700
                    hover:file:bg-violet-100
                "/>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </label>
        </div>

        {{-- Input Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Input Username --}}
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" 
                          class="mt-1 block w-full bg-gray-50" 
                          x-model="username"
                           x-on:input="username = username.toLowerCase().replace(/\s/g, '')"
                          required autocomplete="username" />
            
            <x-input-error class="mt-2" :messages="$errors->get('username')" />

            <p x-show="username.length > 0 && usernameError" 
               x-text="usernameError"
               class="text-red-500 text-xs mt-1"
               x-transition></p>
        </div>

        {{-- Input Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="border-t border-gray-200 pt-4 mt-4">
             <h3 class="text-md font-medium text-gray-900 mb-4">Ubah Password</h3>
             
             {{-- INPUT PASSWORD BARU --}}
            <div class="mt-4">
                <x-input-label for="password" :value="__('New Password')" />
                
                <div class="relative">
                    <x-text-input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="mt-1 block w-full" 
                        autocomplete="new-password" 
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                        x-model="password" 
                    />
                </div>
                
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

            {{-- INPUT KONFIRMASI PASSWORD --}}
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                
                <x-text-input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="mt-1 block w-full" 
                    autocomplete="new-password"
                    x-model="password_confirmation" 
                />
                
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                {{-- Peringatan jika password tidak sama --}}
                <p x-show="password.length > 0 && password_confirmation.length > 0 && password !== password_confirmation" 
                   x-transition
                   class="text-red-500 text-xs mt-2 flex items-center">
                   <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                   Konfirmasi password tidak cocok!
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button 
                ::disabled="!isComplete"
                ::class="!isComplete ? 'opacity-50 cursor-not-allowed' : ''">
                {{ __('Save All Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>