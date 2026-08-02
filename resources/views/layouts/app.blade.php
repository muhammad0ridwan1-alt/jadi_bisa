<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#0284c7">

    <title>JadiBisa — {{ $header ?? 'Platform LMS Bogor EduCARE' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Mobile-first safe area for notch phones */
        body { padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); }
        /* Smooth scrolling for mobile */
        html { -webkit-overflow-scrolling: touch; }
        /* Fix mobile 100vh issue */
        .min-h-screen-mobile { min-height: 100dvh; }
        /* Bottom nav safe area */
        .pb-safe { padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        /* Hide scrollbar on mobile nav */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-[#F8FAFC] selection:bg-sky-500 selection:text-white">
    <div class="min-h-screen-mobile flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" style="display:none" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

        <!-- Sidebar (fixed/sticky on desktop so menu does not scroll with content) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-white border-r border-slate-200/80 text-slate-700 flex-shrink-0 flex flex-col transition-transform duration-300 shadow-lg md:shadow-sm z-50 fixed inset-y-0 left-0 md:sticky md:top-0 md:h-screen md:translate-x-0">
            <!-- Logo Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200/80 bg-white shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="JadiBisa Logo" class="h-8 w-auto">
                    <span class="font-bold text-xl tracking-tight text-slate-900">Jadi<span class="text-sky-600">Bisa</span></span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 active:bg-slate-200 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Navigation Items -->
            <div class="p-3 flex-1 overflow-y-auto hide-scrollbar">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">
                    Navigasi {{ ucfirst(auth()->user()->role) }}
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ (request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('dosen.dashboard') || request()->routeIs('mahasiswa.dashboard_*')) ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        {{ auth()->user()->role === 'mahasiswa' ? 'Mata Kuliah' : 'Dashboard' }}
                    </a>

                    @if(auth()->user()->role === 'mahasiswa')
                        <a href="{{ route('mahasiswa.jadwal') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.jadwal') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Jadwal Kuliah
                        </a>

                        <a href="{{ route('mahasiswa.tugas') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.tugas*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Tugas & Praktikum
                        </a>

                        <a href="{{ route('mahasiswa.nilai') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.nilai') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Nilai & Transkrip
                        </a>

                        <a href="{{ route('mahasiswa.typing') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.typing') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20.17 3.586a2 2 0 010 2.828L11.828 14.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414l8.172-8.172z"></path></svg>
                            Tes Ketik 10 Jari
                        </a>

                        <a href="{{ route('mahasiswa.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.pengumuman') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                    @endif

                    @if(auth()->user()->role === 'dosen')
                        <a href="{{ route('dosen.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.pengumuman') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Buat Pengumuman
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users', 'mahasiswa') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.users') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Kelola User
                        </a>
                        <a href="{{ route('admin.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.pengumuman') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Footer Card -->
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50 flex items-center justify-between gap-2 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-sky-600 font-semibold truncate capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 active:bg-rose-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 relative">
            <!-- Topbar Header -->
            <header class="sticky top-0 h-14 md:h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 shadow-sm shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" class="md:hidden p-1.5 rounded-lg text-slate-500 hover:text-sky-600 hover:bg-slate-100 active:bg-slate-200 transition-colors focus:outline-none shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    @isset($header)
                        <h1 class="text-base md:text-xl font-bold text-slate-900 truncate">{{ $header }}</h1>
                    @endisset
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @if(auth()->user()->role === 'mahasiswa' && auth()->user()->jurusan)
                        <span class="hidden sm:inline-flex text-[11px] bg-sky-50 text-sky-700 border border-sky-200 px-2.5 py-1 rounded-full font-bold">
                            {{ auth()->user()->jurusan }}
                        </span>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="p-2 md:px-3 md:py-1.5 rounded-lg md:rounded-xl text-slate-500 md:text-slate-700 hover:text-sky-600 hover:bg-slate-100 active:bg-slate-200 transition-colors flex items-center gap-1.5 md:bg-white md:border md:border-slate-200 md:shadow-sm">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="hidden md:inline text-xs font-bold">Profile</span>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto pb-safe" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <!-- Flash Success Banner -->
                @if(session('success'))
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Error Banner -->
                @if(session('error'))
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Banner -->
                @if($errors->any())
                    <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm space-y-1">
                        <div class="flex items-center gap-2 font-bold text-sm text-rose-700">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Terdapat kesalahan:
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-0.5 pl-6 text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{ $slot }}
            </main>

            <!-- Mobile Bottom Navigation Bar (Mahasiswa Only) -->
            @if(auth()->user()->role === 'mahasiswa')
            <nav class="md:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-slate-200 pb-safe">
                <div class="grid grid-cols-5 h-14">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 {{ (request()->routeIs('dashboard') || request()->routeIs('mahasiswa.dashboard_*')) ? 'text-sky-600' : 'text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="text-[10px] font-bold">Matkul</span>
                    </a>
                    <a href="{{ route('mahasiswa.jadwal') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('mahasiswa.jadwal') ? 'text-sky-600' : 'text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold">Jadwal</span>
                    </a>
                    <a href="{{ route('mahasiswa.tugas') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('mahasiswa.tugas*') ? 'text-sky-600' : 'text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span class="text-[10px] font-bold">Tugas</span>
                    </a>
                    <a href="{{ route('mahasiswa.nilai') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('mahasiswa.nilai') ? 'text-sky-600' : 'text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="text-[10px] font-bold">Nilai</span>
                    </a>
                    <a href="{{ route('mahasiswa.pengumuman') }}" class="flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('mahasiswa.pengumuman') ? 'text-sky-600' : 'text-slate-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        <span class="text-[10px] font-bold">Info</span>
                    </a>
                </div>
            </nav>
            @endif
        </div>
    </div>

    <!-- AOS Initialization -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 30,
                easing: 'ease-out-cubic',
                disable: window.innerWidth < 768
            });
        });
    </script>
</body>
</html>
