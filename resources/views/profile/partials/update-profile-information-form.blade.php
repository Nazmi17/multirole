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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                {{-- Tampilkan Avatar User (Gunakan Accessor nanti) --}}
<img 
    src="{{ $user->avatar_url }}" 
    alt="{{ $user->name }}" 
    class="rounded-full h-20 w-20 object-cover"
>            </div>
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

        {{-- Input Name (Sudah ada) --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Input Username (Sudah ada) --}}
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" 
                          class="mt-1 block w-full bg-gray-50" 
                          :value="old('username', $user->username)" 
                          required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        {{-- Input Email (Sudah ada) --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            
            {{-- Logic verifikasi email ... --}}
        </div>

        <div class="border-t border-gray-200 pt-4 mt-4">
             <h3 class="text-md font-medium text-gray-900 mb-4">Ubah Password</h3>
             
             {{-- Input New Password --}}
             <div>
                <x-input-label for="password" :value="__('New Password')" />
                {{-- Tambahkan placeholder agar user tahu ini opsional bagi user lama --}}
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="Kosongkan jika tidak ingin mengubah password" />
                
                {{-- Perhatikan: messages ambil dari $errors->get('password'), BUKAN updatePassword --}}
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Input Confirm Password --}}
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Save All Changes') }}</x-primary-button>

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