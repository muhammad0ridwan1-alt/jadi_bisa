<x-guest-layout>
    <div x-data="{ mode: 'login' }" x-init="if (window.location.pathname.includes('register')) mode = 'register'" class="min-h-screen-mobile flex w-full bg-slate-900 overflow-hidden relative">
        <!-- Top Back Button (Static, non-overlapping) -->
        <a href="{{ url('/') }}" class="fixed top-6 left-6 z-50 flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white bg-slate-800/90 hover:bg-slate-800 backdrop-blur-md border border-slate-700/80 shadow-md transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Beranda</span>
        </a>

        <!-- Hardware-Accelerated GPU Sliding Overlay Panel (60fps No-Delay Glide) -->
        <div 
            :class="mode === 'register' ? 'translate-x-0' : 'translate-x-full'"
            class="hidden lg:flex w-1/2 absolute inset-y-0 left-0 z-30 bg-gradient-to-br from-sky-600 via-sky-700 to-slate-900 text-white items-center justify-center p-12 transition-transform duration-500 cubic-bezier(0.4, 0, 0.2, 1) transform-gpu shadow-2xl border-r border-white/10"
        >
            <div class="relative z-10 p-8 sm:p-10 bg-slate-900/40 backdrop-blur-md rounded-2xl border border-white/10 text-center max-w-md shadow-2xl space-y-6">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-white backdrop-blur-md shadow-lg p-2 overflow-hidden transition-transform duration-300 hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="JadiBisa Logo" class="h-full w-auto object-contain">
                </div>

                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-white tracking-tight">Jadi<span class="text-sky-400">Bisa</span></h2>
                    <span class="inline-block px-3 py-1 bg-white/10 text-sky-200 text-xs font-bold rounded-full border border-white/10">
                        Platform LMS Bogor EduCARE
                    </span>
                </div>

                <!-- Text for Login Mode -->
                <div x-show="mode === 'login'" class="space-y-4">
                    <p class="text-slate-200 text-sm leading-relaxed font-normal">
                        Platform belajar perkuliahan khusus mahasiswa & dosen BEC. Dari Yang Belum Bisa, Jadi Lebih Bisa.
                    </p>
                    <button 
                        @click="mode = 'register'; window.history.replaceState({}, '', '/register')" 
                        class="px-6 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs border border-white/20 transition-all active:scale-95 shadow-sm"
                    >
                        Belum punya akun? Daftar →
                    </button>
                </div>

                <!-- Text for Register Mode -->
                <div x-show="mode === 'register'" style="display:none;" class="space-y-4">
                    <p class="text-slate-200 text-sm leading-relaxed font-normal">
                        Bergabunglah dengan ribuan mahasiswa BEC dan mulai pengalaman belajar digitalmu sekarang.
                    </p>
                    <button 
                        @click="mode = 'login'; window.history.replaceState({}, '', '/login')" 
                        class="px-6 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-xs border border-white/20 transition-all active:scale-95 shadow-sm"
                    >
                        Sudah punya akun? Masuk →
                    </button>
                </div>
            </div>
        </div>

        <!-- Fixed Form Layout Container -->
        <div class="w-full flex min-h-screen relative">
            <!-- Left Side: Login Form -->
            <div 
                :class="mode === 'login' ? 'block' : 'hidden lg:flex'"
                class="w-full lg:w-1/2 min-h-screen flex flex-col justify-center items-center p-6 sm:p-12 bg-white z-10"
            >
                <div class="w-full max-w-md space-y-6 pt-12 sm:pt-0">
                    <div class="space-y-1.5 text-left">
                        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Portal Masuk</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Selamat Datang Kembali</h2>
                        <p class="text-slate-500 text-xs sm:text-sm">Masukkan email & password akun Anda untuk masuk.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Email Mahasiswa / Dosen</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="email" name="email" :value="old('email')" placeholder="nama@jadibisa.com" required autofocus autocomplete="username" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div class="space-y-1">
                            <div class="flex justify-between items-center">
                                <label class="block text-xs font-bold text-slate-700">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="text-xs font-semibold text-sky-600 hover:underline" href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div class="flex items-center">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500 w-4 h-4" name="remember">
                                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-extrabold text-white bg-sky-600 hover:bg-sky-700 active:bg-sky-800 transition-all shadow-md shadow-sky-600/20 active:scale-95">
                            Masuk Sekarang →
                        </button>
                    </form>

                    <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
                        Belum memiliki akun Mahasiswa? 
                        <button @click="mode = 'register'; window.history.replaceState({}, '', '/register')" class="font-bold text-sky-600 hover:underline">Daftar sekarang</button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Register Form -->
            <div 
                :class="mode === 'register' ? 'block' : 'hidden lg:flex'"
                class="w-full lg:w-1/2 min-h-screen flex flex-col justify-center items-center p-6 sm:p-12 bg-white z-10"
            >
                <div class="w-full max-w-md space-y-5 pt-12 sm:pt-0">
                    <div class="space-y-1.5 text-left">
                        <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Pendaftaran Akun</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Buat Akun Mahasiswa</h2>
                        <p class="text-slate-500 text-xs sm:text-sm">Isi data lengkap Anda untuk bergabung di JadiBisa.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="text" name="name" :value="old('name')" placeholder="Nama Lengkap Mahasiswa" required autocomplete="name" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="email" name="email" :value="old('email')" placeholder="email@gmail.com" required autocomplete="username" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Program Studi / Jurusan BEC</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                </div>
                                <select name="jurusan" required class="block w-full pl-9 pr-8 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm bg-white">
                                    <option value="" disabled selected>Pilih Program Studi</option>
                                    <option value="Administrasi Perkantoran" {{ old('jurusan') == 'Administrasi Perkantoran' ? 'selected' : '' }}>Administrasi Perkantoran</option>
                                    <option value="Bisnis Manajemen" {{ old('jurusan') == 'Bisnis Manajemen' ? 'selected' : '' }}>Bisnis Manajemen</option>
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('jurusan')" class="mt-1" />
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="password" name="password" placeholder="Buat password baru" required autocomplete="new-password" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Konfirmasi Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input class="block w-full pl-9 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-sky-600 text-xs sm:text-sm" type="password" name="password_confirmation" placeholder="Ulangi password baru" required autocomplete="new-password" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>

                        <button type="submit" class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-extrabold text-white bg-sky-600 hover:bg-sky-700 active:bg-sky-800 transition-all shadow-md shadow-sky-600/20 active:scale-95">
                            Daftar Mahasiswa →
                        </button>
                    </form>

                    <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
                        Sudah memiliki akun? 
                        <button @click="mode = 'login'; window.history.replaceState({}, '', '/login')" class="font-bold text-sky-600 hover:underline">Masuk di sini</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
