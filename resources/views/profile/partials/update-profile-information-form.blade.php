<section x-data="{
        // === 1. STATE VARIABLES ===
        showConfirmModal: false,
        
        // Menentukan metode default: Jika user tidak punya password (user Google), default ke 'email', jika tidak ke 'password'
        authMethod: '{{ empty($user->password) ? 'email' : 'password' }}',
        
        authPassword: '',
        authOtp: '',
        authEmailOtp: '',
        
        isLoading: false,
        authError: null,
        verificationStatus: '',

        // Email State
        sendingEmail: false,
        emailSent: false,
        emailTimer: 0,

        // === 2. FUNGSI LOGIC ===

        initiateSubmit() {
            this.verificationStatus = '';
            axios.get('/auth/status')
                .then(response => {
                    if (response.data.confirmed) {
                        this.$refs.profileForm.submit();
                    } else {
                        this.showConfirmModal = true;
                        this.resetModal();
                    }
                });
        },

        resetModal() {
            this.authPassword = '';
            this.authOtp = '';
            this.authEmailOtp = '';
            this.authError = null;
            // Kita reset pilihan metode ke default user
            this.authMethod = '{{ empty($user->password) ? 'email' : 'password' }}';
        },

        // Fungsi helper untuk membersihkan input saat ganti metode
        changeMethod() {
            this.authError = null;
            this.authPassword = '';
            this.authOtp = '';
            // Note: authEmailOtp tidak di-reset supaya kalau user bolak-balik, kodenya masih ada
        },

        sendEmailOtp() {
            this.sendingEmail = true;
            this.authError = null;
            axios.post('/auth/send-email-otp')
                .then(res => {
                    this.emailSent = true;
                    this.sendingEmail = false;
                    this.emailTimer = 60;
                    let interval = setInterval(() => {
                        this.emailTimer--;
                        if (this.emailTimer <= 0) clearInterval(interval);
                    }, 1000);
                })
                .catch(err => {
                    this.sendingEmail = false;
                    this.authError = 'Gagal mengirim email.';
                });
        },

        confirmAuth() {
            this.isLoading = true;
            this.authError = null;

            // Kita hanya kirim data sesuai metode yang dipilih agar tidak bentrok di backend
            let payload = {};
            
            if (this.authMethod === 'password') payload.password = this.authPassword;
            if (this.authMethod === 'email') payload.email_otp = this.authEmailOtp;
            if (this.authMethod === 'google') payload.otp = this.authOtp;

            axios.post('{{ route('password.confirm') }}', payload, {
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(response => {
                this.showConfirmModal = false;
                this.isLoading = false;
                this.verificationStatus = '✅ Verifikasi berhasil! Menyimpan...';
                setTimeout(() => { this.$refs.profileForm.submit(); }, 1000);
            })
            .catch(error => {
                this.isLoading = false;
                if (error.response && error.response.data.errors) {
                    this.authError = Object.values(error.response.data.errors).flat()[0];
                } else if (error.response && error.response.data.message) {
                    this.authError = error.response.data.message;
                } else {
                    this.authError = 'Verifikasi gagal.';
                }
            });
        }
    }">

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Update username, nama, dan password akun Anda.
        </p>
    </header>

    {{-- Form Verifikasi Email (Bawaan Breeze) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- FORM UTAMA PROFILE --}}
    <form x-ref="profileForm" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data"
        x-data="{ 
            username: '{{ old('username', $user->username) }}',
            password: '', 
            password_confirmation: '',
            
            requirements: [
                { id: 1, regex: /.{8,}/, text: 'Minimal 8 karakter' },
                { id: 2, regex: /[A-Z]/, text: 'Harus ada huruf besar' },
                { id: 3, regex: /[0-9]/, text: 'Harus ada angka' },
                { id: 4, regex: /[^A-Za-z0-9]/, text: 'Harus ada simbol (!@#$%^&*)' }
            ],

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
                if (this.username.length === 0 || this.usernameError) return false;
                if (this.password === '') return true;
                return this.requirements.every(req => req.regex.test(this.password)) && 
                       this.password === this.password_confirmation;
            }
        }">
        
        @csrf
        @method('patch')

        {{-- === BAGIAN INPUT (AVATAR, NAMA, EMAIL, DLL) === --}}
        
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                {{-- Pastikan path avatar_url helper benar --}}
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-full h-20 w-20 object-cover bg-gray-200">
            </div>
            <label class="block">
                <span class="sr-only">Choose profile photo</span>
                <input type="file" name="avatar" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </label>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" autofocus="autofocus" class="mt-1 block w-full" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full bg-gray-50" x-model="username" x-on:input="username = username.toLowerCase().replace(/\s/g, '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
            <p x-show="username.length > 0 && usernameError" x-text="usernameError" class="text-red-500 text-xs mt-1" x-transition></p>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            
           {{-- LOGIKA TAMPILAN: Jika user login tapi email belum verified --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-gray-800">
                    <p class="text-sm">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- @if($user->google_id)
            <div class="mb-4 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm border border-blue-200">
                <strong class="font-bold">Info Akun Google:</strong><br>
                Anda login menggunakan Google. Anda tidak perlu memasukkan password lama untuk melakukan perubahan.
            </div>
        @endif --}}
        
        <div class="border-t border-gray-200 pt-4 mt-4">
             <h3 class="text-md font-medium text-gray-900 mb-4">Ubah Password</h3>
             <div class="mt-4">
                <x-input-label for="password" :value="__('New Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="Kosongkan jika tidak ingin mengubah" x-model="password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                
                <div class="mt-2" x-show="password.length > 0" x-transition>
                    <ul class="text-xs space-y-1">
                        <template x-for="req in sortedRequirements" :key="req.id">
                            <li class="flex items-center transition-all" :class="req.met ? 'text-green-600 font-bold translate-x-1' : 'text-gray-500'">
                                <span class="mr-2" x-text="req.met ? '✓' : '○'"></span><span x-text="req.text"></span>
                            </li>
                        </template>
                    </ul>
                </div>
             </div>

             <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" x-model="password_confirmation" />
                <p x-show="password.length > 0 && password !== password_confirmation" class="text-red-500 text-xs mt-2">Konfirmasi password tidak cocok!</p>
             </div>
        </div>

        {{-- === TOMBOL SAVE & PESAN SUKSES === --}}
        <div class="flex flex-col gap-2 mt-6">
            
            <div x-show="verificationStatus" 
                 x-transition
                 class="p-2 mb-2 text-sm text-green-700 bg-green-100 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span x-text="verificationStatus" class="font-medium"></span>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button type="button" 
                    @click="initiateSubmit"
                    ::disabled="!isComplete"
                    ::class="!isComplete ? 'opacity-50 cursor-not-allowed' : ''">
                    {{ __('Save All Changes') }}
                </x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>
    </form>

   {{-- === MODAL KONFIRMASI === --}}
    <div x-show="showConfirmModal" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all" @click.away="showConfirmModal = false">
            
            {{-- Header Modal --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('Verifikasi Keamanan') }}</h2>
                <button @click="showConfirmModal = false" class="text-gray-400 hover:text-gray-500">&times;</button>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">
                Pilih metode verifikasi untuk melanjutkan.
            </p>

            {{-- 1. DROPDOWN PEMILIH METODE --}}
            <div class="mb-6">
                <x-input-label for="auth_method" value="Metode Verifikasi" class="mb-1" />
                <select id="auth_method" 
                        x-model="authMethod" 
                        @change="changeMethod"
                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    
                    {{-- Opsi Password (Hanya muncul jika user punya password) --}}
                    @if(!empty($user->password))
                        <option value="password">Password Akun</option>
                    @endif
                    
                    <option value="email">Kode OTP Email ({{ $user->email }})</option>
                    
                    {{-- Opsi Google Auth (Hanya muncul jika user sudah setup 2FA) --}}
                    @if(!empty($user->google2fa_secret))
                        <option value="google">Google Authenticator App</option>
                    @endif
                </select>
            </div>

            {{-- 2. AREA INPUT DINAMIS (Muncul berdasarkan Pilihan Select) --}}
            <div class="min-h-[100px]"> {{-- Min-height supaya modal ga 'lompat' ukurannya --}}
                
                {{-- A. INPUT PASSWORD --}}
                <div x-show="authMethod === 'password'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <x-input-label for="auth_password" value="Masukkan Password" />
                    <x-text-input id="auth_password" type="password" class="block mt-1 w-full"
                                  placeholder="********"
                                  x-model="authPassword" 
                                  @keydown.enter="confirmAuth" />
                    <p class="text-xs text-gray-500 mt-2">Gunakan password yang biasa Anda gunakan untuk login.</p>
                </div>

                {{-- B. INPUT EMAIL OTP --}}
                <div x-show="authMethod === 'email'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <x-input-label for="auth_email_otp" value="Kode OTP Email" />
                    
                    <div class="flex gap-2 mt-1">
                        <x-text-input id="auth_email_otp" type="text" 
                                      class="block w-full text-center tracking-widest font-mono"
                                      placeholder="------"
                                      maxlength="6"
                                      x-model="authEmailOtp" 
                                      @keydown.enter="confirmAuth" />
                        
                        <button type="button" 
                                @click="sendEmailOtp" 
                                :disabled="sendingEmail || emailTimer > 0"
                                :class="{ 'opacity-50 cursor-not-allowed': sendingEmail || emailTimer > 0 }"
                                class="shrink-0 px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 focus:outline-none transition ease-in-out duration-150 min-w-[110px]">
                            <span x-text="sendingEmail ? 'Mengirim...' : (emailTimer > 0 ? emailTimer + 's' : 'Kirim Kode')"></span>
                        </button>
                    </div>
                    <p x-show="emailSent" class="text-xs text-green-600 mt-2">✓ Kode terkirim. Cek Inbox/Spam.</p>
                    <p x-show="!emailSent" class="text-xs text-gray-500 mt-2">Klik tombol kirim untuk mendapatkan kode.</p>
                </div>

                {{-- C. INPUT GOOGLE AUTH --}}
                <div x-show="authMethod === 'google'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <x-input-label for="auth_otp" value="Kode Authenticator" />
                    <x-text-input id="auth_otp" type="text" class="block mt-1 w-full text-center tracking-widest font-mono text-lg"
                                  placeholder="000 000"
                                  maxlength="6"
                                  x-model="authOtp" 
                                  @keydown.enter="confirmAuth" />
                    <p class="text-xs text-gray-500 mt-2">Buka aplikasi Google Authenticator Anda.</p>
                </div>

            </div>

            {{-- Pesan Error --}}
            <div x-show="authError" class="mt-4 p-2 bg-red-50 border border-red-200 rounded text-red-600 text-sm flex items-center" x-transition>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span x-text="authError"></span>
            </div>

            {{-- Footer --}}
            <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                <x-secondary-button @click="showConfirmModal = false">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ml-3" @click="confirmAuth" ::disabled="isLoading">
                    <span x-show="!isLoading">{{ __('Konfirmasi') }}</span>
                    <span x-show="isLoading">{{ __('Memproses...') }}</span>
                </x-primary-button>
            </div>
        </div>
    </div>
</section>