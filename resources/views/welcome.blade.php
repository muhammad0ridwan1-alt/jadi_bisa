<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0284c7">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>JadiBisa — Platform LMS Bogor EduCARE</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-[#F8FAFC] selection:bg-sky-500 selection:text-white">
    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all duration-300" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2 sm:gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="JadiBisa Logo" class="h-7 sm:h-9 w-auto object-contain">
                    <span class="font-black text-xl sm:text-2xl tracking-tight text-slate-900">Jadi<span class="text-sky-600">Bisa</span></span>
                </a>
                
                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#beranda" class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors">Beranda</a>
                    <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors">Fitur</a>
                    <a href="#jurusan" class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors">Program Studi</a>
                    <a href="#faq" class="text-sm font-semibold text-slate-600 hover:text-sky-600 transition-colors">FAQ</a>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-sm font-extrabold shadow-sm transition-all hover:scale-105">Dashboard →</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-slate-700 hover:text-sky-600 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-sm font-extrabold shadow-sm transition-all hover:scale-105">Daftar Mahasiswa</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-500 hover:text-sky-600 hover:bg-slate-100 active:bg-slate-200 transition-colors">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenu" style="display:none" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Dropdown -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" style="display:none" class="md:hidden bg-white border-t border-slate-100 shadow-lg">
            <div class="px-4 py-3 space-y-1">
                <a href="#beranda" @click="mobileMenu = false" class="block px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-sky-600 hover:bg-sky-50 rounded-lg active:bg-sky-100">Beranda</a>
                <a href="#fitur" @click="mobileMenu = false" class="block px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-sky-600 hover:bg-sky-50 rounded-lg active:bg-sky-100">Fitur</a>
                <a href="#jurusan" @click="mobileMenu = false" class="block px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-sky-600 hover:bg-sky-50 rounded-lg active:bg-sky-100">Program Studi</a>
                <a href="#faq" @click="mobileMenu = false" class="block px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-sky-600 hover:bg-sky-50 rounded-lg active:bg-sky-100">FAQ</a>
            </div>
            <div class="px-4 pb-4 pt-2 border-t border-slate-100 space-y-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-sm font-extrabold shadow-sm active:scale-95 transition-all">Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-slate-100 text-slate-800 text-sm font-bold transition-colors active:bg-slate-200">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-sm font-extrabold shadow-sm active:scale-95 transition-all">Daftar Mahasiswa</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="py-12 sm:py-20 lg:py-24 bg-white border-b border-slate-200/80 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-4 sm:space-y-6">
                <span data-aos="fade-down" class="inline-flex items-center gap-2 px-3 sm:px-4 py-1 sm:py-1.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[11px] sm:text-xs font-extrabold shadow-sm">
                    LMS Bogor EduCARE
                </span>
                
                <h1 data-aos="zoom-in" data-aos-delay="100" class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Platform Pembelajaran Digital <br>
                    <span class="text-sky-600">Bogor EduCARE</span>
                </h1>
                
                <p data-aos="fade-up" data-aos-delay="200" class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal">
                    Akses modul perkuliahan, tugas praktikum, jadwal kelas, kuis evaluasi, dan sertifikasi Blind System Typing dalam satu portal terpadu.
                </p>

                <div data-aos="fade-up" data-aos-delay="300" class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="{{ route('login') }}" class="px-6 sm:px-8 py-3 sm:py-3.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm sm:text-base shadow-md shadow-sky-600/20 hover:scale-105 transition-all text-center active:scale-95">
                        Masuk Portal LMS
                    </a>
                    <a href="#jurusan" class="px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm sm:text-base transition-colors text-center border border-slate-200 active:bg-slate-300">
                        Program Studi
                    </a>
                </div>

                <!-- Stats summary -->
                <div data-aos="fade-up" data-aos-delay="400" class="pt-6 sm:pt-10 grid grid-cols-3 gap-4 sm:gap-6 border-t border-slate-100 max-w-xl">
                    <div>
                        <span class="block text-2xl sm:text-3xl font-black text-slate-900">{{ $mahasiswaCount }}</span>
                        <span class="text-[10px] sm:text-xs text-slate-500 font-semibold">Mahasiswa Terdaftar</span>
                    </div>
                    <div>
                        <span class="block text-2xl sm:text-3xl font-black text-sky-600">{{ $modulCount }}</span>
                        <span class="text-[10px] sm:text-xs text-slate-500 font-semibold">Modul Materi</span>
                    </div>
                    <div>
                        <span class="block text-2xl sm:text-3xl font-black text-indigo-600">{{ $quizCount }}</span>
                        <span class="text-[10px] sm:text-xs text-slate-500 font-semibold">Kuis Evaluasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="py-12 sm:py-20 bg-[#F8FAFC] border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 sm:mb-12 space-y-1" data-aos="fade-up">
                <span class="text-[11px] sm:text-xs font-extrabold text-sky-600 uppercase tracking-wider">Fitur Utama</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Layanan Pembelajaran</h2>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                <!-- Fitur 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="bg-white p-4 sm:p-7 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-sky-300 transition-all space-y-2 sm:space-y-3 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl sm:text-2xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Modul PDF & Video</h3>
                    <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed font-medium hidden sm:block">
                        Unduh materi ajar PDF dan tonton video pengajaran dosen.
                    </p>
                </div>

                <!-- Fitur 2 -->
                <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-4 sm:p-7 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all space-y-2 sm:space-y-3 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl sm:text-2xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Kuis Evaluasi</h3>
                    <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed font-medium hidden sm:block">
                        Latihan evaluasi berkala untuk menguji pemahaman materi.
                    </p>
                </div>

                <!-- Fitur 3 -->
                <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-4 sm:p-7 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all space-y-2 sm:space-y-3 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl sm:text-2xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Tugas Praktikum</h3>
                    <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed font-medium hidden sm:block">
                        Pengumpulan berkas tugas praktikum dengan batas waktu jelas.
                    </p>
                </div>

                <!-- Fitur 4 -->
                <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-4 sm:p-7 rounded-xl sm:rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-300 transition-all space-y-2 sm:space-y-3 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl sm:text-2xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Transkrip & KHS</h3>
                    <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed font-medium hidden sm:block">
                        Pantau nilai, IPK berjalan, dan unduh KHS PDF otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi (AP & BM) -->
    <section id="jurusan" class="py-12 sm:py-20 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 sm:mb-12 space-y-1" data-aos="fade-up">
                <span class="text-[11px] sm:text-xs font-extrabold text-sky-600 uppercase tracking-wider">Program Keahlian</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Program Studi</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8">
                <!-- Jurusan 1 AP -->
                <div data-aos="fade-right" class="bg-[#F8FAFC] p-5 sm:p-8 rounded-xl sm:rounded-2xl border border-slate-200/80 flex flex-col justify-between space-y-4 sm:space-y-6 hover:shadow-md transition-all">
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-sky-100 text-sky-700 text-[11px] sm:text-xs font-extrabold rounded-md">Administrasi Perkantoran</span>
                            <span class="text-[11px] sm:text-xs text-slate-400 font-bold">Kelas 3A - 3F</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">Administrasi Perkantoran (AP)</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                            Keahlian tata kelola kantor modern, kearsipan elektronik, korespondensi bisnis, dan aplikasi perkantoran.
                        </p>
                    </div>
                    <a href="{{ route('register') }}" class="block w-full text-center py-2.5 sm:py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-sm rounded-xl transition-colors active:scale-95">
                        Daftar Mahasiswa AP →
                    </a>
                </div>

                <!-- Jurusan 2 BM -->
                <div data-aos="fade-left" class="bg-[#F8FAFC] p-5 sm:p-8 rounded-xl sm:rounded-2xl border border-slate-200/80 flex flex-col justify-between space-y-4 sm:space-y-6 hover:shadow-md transition-all">
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 bg-emerald-100 text-emerald-700 text-[11px] sm:text-xs font-extrabold rounded-md">Bisnis & Manajemen</span>
                            <span class="text-[11px] sm:text-xs text-slate-400 font-bold">Kelas 3G - 3H</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">Bisnis & Manajemen (BM)</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                            Keahlian pemasaran digital, manajemen keuangan, creative content, dan kewirausahaan bisnis.
                        </p>
                    </div>
                    <a href="{{ route('register') }}" class="block w-full text-center py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl transition-colors active:scale-95">
                        Daftar Mahasiswa BM →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Accordion -->
    <section id="faq" class="py-12 sm:py-20 bg-[#F8FAFC]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <div class="text-center mb-8 sm:mb-12 space-y-1">
                <span class="text-[11px] sm:text-xs font-extrabold text-sky-600 uppercase tracking-wider">Informasi</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Tanya Jawab</h2>
            </div>

            <div class="space-y-3 sm:space-y-4" x-data="{ active: null }">
                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden">
                    <button @click="active = active === 1 ? null : 1" class="w-full p-5 text-left font-bold text-slate-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Bagaimana cara mahasiswa masuk ke portal?</span>
                        <span class="text-sky-600 font-bold" x-text="active === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 1" x-transition class="p-5 pt-0 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-200/60 font-medium">
                        Gunakan email kelasan terdaftar atau daftar akun baru melalui tombol <strong>Daftar Mahasiswa</strong>.
                    </div>
                </div>

                <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/80 overflow-hidden">
                    <button @click="active = active === 2 ? null : 2" class="w-full p-5 text-left font-bold text-slate-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Bagaimana cara mengakses modul & mengumpulkan tugas?</span>
                        <span class="text-sky-600 font-bold" x-text="active === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 2" x-transition class="p-5 pt-0 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-200/60 font-medium">
                        Setelah login, pilih mata kuliah pada dashboard untuk mengunduh materi PDF, menonton video, dan mengunggah tugas praktikum.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-6 sm:py-8 bg-slate-900 text-slate-400 text-center text-[11px] sm:text-xs font-semibold">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-1">
            <p>&copy; 2026 JadiBisa — Platform LMS Perkuliahan Bogor EduCARE.</p>
        </div>
    </footer>

    <!-- AOS Initialization -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    </script>
</body>
</html>
