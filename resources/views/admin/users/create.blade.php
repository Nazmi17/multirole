<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('admin.users.store') }}" 
                x-data="{
                    name: '',
                    email: '',
                    username: '', 
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
                        // Cek apakah semua requirement terpenuhi (strength === 4)
                        // DAN password sama dengan konfirmasi password
                        return this.strength === 4 && this.password === this.password_confirmation;
                    }
                }">
                
                @csrf

                <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Masukkan Username" x-model="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" x-model="username" value="{{ old('username') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Contoh: budi_santoso">
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" x-model="email" placeholder="Masukkan Email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role / Jabatan</label>
                        <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @if (auth()->user()->hasRole('admin'))
                                <option value="">-- Pilih Role --</option>
                            @endif
                           @foreach($roles as $role)
                                @php
                                    // Logika penamaan: Admin -> Super Admin, Pengelola -> Admin
                                    $displayRole = match($role->name) {
                                        'admin'     => 'Super Admin',
                                        'Pengelola' => 'Admin',
                                        default     => ucfirst($role->name)
                                    };
                                @endphp

                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    
                    <div class="relative">
                        <input type="password" name="password" id="password" x-model="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

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
                                    
                                    <span class="mr-2">
                                        <template x-if="req.regex.test(password)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </template>
                                        <template x-if="!req.regex.test(password)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </template>
                                    </span>
                                    <span x-text="req.text"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        x-model="password_confirmation" 
                        placeholder="Ulangi password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        
                    <p x-show="password.length > 0 && password_confirmation.length > 0 && password !== password_confirmation" 
                    class="text-red-500 text-xs mt-1">
                    Password tidak sama!
                    </p>
                </div>

                <div class="flex items-center justify-end mt-4 gap-6">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm justify-self-start">Batal</a>
                    
                    <button type="submit" 
                        :disabled="!isComplete"
                        :class="!isComplete ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                        class="text-white font-bold py-2 px-4 rounded shadow-sm text-sm transition-colors duration-200">
                        Simpan Pengguna
                    </button>
                </div>

            </form>

            </div>
        </div>
    </div>
</x-app-layout>

@section('validation')
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong>{{ __('Whoops!') }}</strong>
            <span class="block sm:inline">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
        </div>
    @endif
@endsection
