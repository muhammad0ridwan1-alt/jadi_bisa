<x-guest-layout>
    <div class="mb-4 text-xs sm:text-sm text-slate-600">
        Masukkan alamat email terdaftar Anda untuk menerima tautan reset password.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email Terdaftar</label>
            <x-text-input id="email" class="block w-full text-xs sm:text-sm rounded-xl" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="rounded-xl text-xs font-bold">
                Kirim Link Reset Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
