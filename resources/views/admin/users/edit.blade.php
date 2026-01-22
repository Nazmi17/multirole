<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.users.update', $user) }}" 
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
                            // LOGIKA KHUSUS EDIT:
                            // 1. Jika password kosong (tidak mau ganti), maka Valid (true)
                            if (this.password === '') return true;

                            // 2. Jika diisi, harus valid DAN konfirmasi cocok
                            return this.strength === 4 && this.password === this.password_confirmation;
                        }
                    }">
                    
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="name" id="name" placeholder="Masukkan Username" value="{{ old('name', $user->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukkan Email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role / Jabatan</label>
                        <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                @php
                                    // Logika penamaan sama
                                    $displayRole = match($role->name) {
                                        'admin'     => 'Super Admin',
                                        'Pengelola' => 'Admin',
                                        default     => ucfirst($role->name)
                                    };
                                    $currentRole = $user->roles->first()->name ?? '';
                                @endphp

                                <option value="{{ $role->name }}" 
                                    {{ (old('role', $currentRole) == $role->name) ? 'selected' : '' }}>
                                    {{ $displayRole }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status Akun</label>
                        <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            {{-- Logika untuk Edit: Cek value database --}}
                            {{-- Logika untuk Create: Default Active (1) --}}
                            @php
                                $currentStatus = isset($user) ? $user->is_active : 1; 
                            @endphp
                            
                            <option value="1" {{ $currentStatus == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $currentStatus == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- <div class="border-t border-gray-200 my-6"></div> --}}
                    <div class="border-t border-gray-200 pt-4 mt-4">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Ubah Password (Opsional)</h3>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" x-model="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                    <div class="mt-2" x-show="password.length > 0" x-transition>
                        {{-- Sama seperti sebelumnya --}}
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

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        x-model="password_confirmation" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    
                    <p x-show="password.length > 0 && password_confirmation.length > 0 && password !== password_confirmation" 
                    class="text-red-500 text-xs mt-1">
                    Password tidak sama!
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4 gap-6">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Batal</a>
                
                <button type="submit" 
                    :disabled="!isComplete"
                    :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                    class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>

        </form>
            </div>
        </div>
    </div>
</x-app-layout>