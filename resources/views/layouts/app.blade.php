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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); }
        html { -webkit-overflow-scrolling: touch; }
        .min-h-screen-mobile { min-height: 100dvh; }
        .pb-safe { padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-[#F8FAFC] selection:bg-sky-500 selection:text-white">
    @if(session()->has('impersonator_id'))
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-bold px-4 py-2.5 text-xs flex items-center justify-between shadow-md z-50 sticky top-0 border-b border-amber-600">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-slate-900 animate-ping"></span>
                <span>Mode Pratinjau Administrator: Anda sedang melihat sistem sebagai <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}{{ auth()->user()->kelas ? ' • Kelas ' . auth()->user()->kelas : '' }}{{ auth()->user()->angkatan ? ' • Angkatan ' . auth()->user()->angkatan : '' }})</span>
            </div>
            <a href="{{ route('impersonate.leave') }}" class="px-3.5 py-1.5 bg-slate-950 hover:bg-slate-900 text-white rounded-xl text-xs font-black transition-transform active:scale-95 shadow-sm inline-flex items-center gap-1">
                <span>← Kembali ke Akun Admin</span>
            </a>
        </div>
    @endif
    <div class="min-h-screen-mobile flex flex-col md:flex-row" x-data="{ sidebarOpen: false }">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" style="display:none" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

        <!-- Sidebar Navigation (Clean Light Theme matching rest of website) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-white border-r border-slate-200/80 text-slate-700 flex-shrink-0 flex flex-col transition-transform duration-300 shadow-lg md:shadow-xs z-50 fixed inset-y-0 left-0 md:sticky md:top-0 md:h-screen md:translate-x-0">
            <!-- Logo Header -->
            <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200/80 bg-white shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="JadiBisa Logo" class="h-8 w-auto">
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">Jadi<span class="text-sky-600">Bisa</span></span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Navigation Items -->
            <div class="p-3 flex-1 overflow-y-auto hide-scrollbar">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">
                    Navigasi {{ ucfirst(auth()->user()->role) }}
                </div>
                
                <nav class="space-y-1">
                    @if(auth()->user()->role === 'mahasiswa')
                        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dashboard') || request()->routeIs('mahasiswa.dashboard_*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Mata Kuliah
                        </a>

                        <a href="{{ route('mahasiswa.jadwal') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.jadwal*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Jadwal Kuliah
                        </a>

                        <a href="{{ route('mahasiswa.tugas') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.tugas*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Tugas & Praktikum
                        </a>

                        <a href="{{ route('mahasiswa.nilai') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.nilai') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Nilai & Transkrip
                        </a>

                        <a href="{{ route('mahasiswa.simulator_ipk') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.simulator_ipk') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Simulator Target IPK
                        </a>

                        <a href="{{ route('mahasiswa.agenda') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.agenda') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Agenda & To-Do
                        </a>

                        <a href="{{ route('mahasiswa.focus') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.focus') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Ruang Fokus Belajar
                        </a>

                        <a href="{{ route('mahasiswa.typing') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.typing') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20.17 3.586a2 2 0 010 2.828L11.828 14.828a2 2 0 01-1.414.586H8v-2.414a2 2 0 01.586-1.414l8.172-8.172z"></path></svg>
                            Tes Ketik 10 Jari
                        </a>

                        <a href="{{ route('mahasiswa.peringkat') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.peringkat') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Peringkat & Leaderboard
                        </a>

                        <a href="{{ route('mahasiswa.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('mahasiswa.pengumuman') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                    @endif

                    @if(auth()->user()->role === 'dosen')
                        <a href="{{ route('dosen.dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.dashboard') || request()->routeIs('dosen.kelas*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Kelas & Mata Kuliah
                        </a>
                        <a href="{{ route('dosen.modul') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.modul*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Modul & Materi
                        </a>
                        <a href="{{ route('dosen.tugas') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.tugas*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Tugas & Praktikum
                        </a>
                        <a href="{{ route('dosen.penilaian') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.penilaian*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Penilaian & Nilai
                        </a>
                        <a href="{{ route('dosen.jadwal') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.jadwal*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Jadwal Mengajar
                        </a>
                        <a href="{{ route('dosen.mahasiswa') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.mahasiswa*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Daftar Mahasiswa
                        </a>
                        <a href="{{ route('dosen.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('dosen.pengumuman*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard Admin
                        </a>
                        <a href="{{ route('admin.angkatan') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.angkatan*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Master Angkatan
                        </a>
                        <a href="{{ route('admin.kelas_management') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.kelas_management*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Master Kelas
                        </a>
                        <a href="{{ route('admin.matkul') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.matkul*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Master Mata Kuliah
                        </a>
                        <a href="{{ route('admin.jadwal') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.jadwal*') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Kelola Jadwal & Cawu
                        </a>
                        <a href="{{ route('admin.users', 'mahasiswa') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.users') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Kelola User
                        </a>
                        <a href="{{ route('admin.pengumuman') }}" @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 {{ request()->routeIs('admin.pengumuman') ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-sky-600' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman
                        </a>
                    @endif
                </nav>
            </div>

            <!-- User Footer Card -->
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50 flex items-center justify-between gap-2 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
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
            <!-- Topbar Header (Clean White Glassmorphism Header matching all pages) -->
            <header class="sticky top-0 h-14 md:h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 shadow-xs shrink-0">
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
                        <span class="hidden sm:inline-flex text-[11px] bg-sky-50 text-sky-700 border border-sky-200 px-3 py-1 rounded-full font-bold">
                            {{ auth()->user()->jurusan }}
                        </span>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="p-2 md:px-3 md:py-1.5 rounded-lg md:rounded-xl text-slate-600 hover:text-sky-600 hover:bg-slate-100 active:bg-slate-200 transition-colors flex items-center gap-1.5 md:bg-white md:border md:border-slate-200 md:shadow-xs">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="hidden md:inline text-xs font-bold text-slate-700">Profile</span>
                    </a>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto pb-safe" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <!-- Flash Success Banner -->
                @if(session('success'))
                    <div class="mb-4 sm:mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Flash Error Banner -->
                @if(session('error'))
                    <div class="mb-4 sm:mb-6 p-4 rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Validation Errors Banner -->
                @if(isset($errors) && $errors->any())
                    <div class="mb-4 sm:mb-6 p-4 rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm space-y-1">
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
        </div>
    </div>

    <!-- 🔔 Realtime Notification Toast Container -->
    <div id="notification-toast-container" class="fixed bottom-4 right-4 sm:top-20 sm:bottom-auto sm:right-6 z-50 flex flex-col gap-3 max-w-[92vw] sm:max-w-sm pointer-events-none"></div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 600, once: true });

        // 🎵 Web Audio API Synthesized Crystal Bell Chime
        function playNotificationChime() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                if (ctx.state === 'suspended') {
                    ctx.resume();
                }

                const now = ctx.currentTime;

                // Bell Tone 1: Note A5 (880 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(880, now);
                gain1.gain.setValueAtTime(0.25, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.45);

                // Bell Tone 2: Note D6 (1174.66 Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1174.66, now + 0.12);
                gain2.gain.setValueAtTime(0.3, now + 0.12);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.65);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.12);
                osc2.stop(now + 0.65);
            } catch (e) {
                console.warn('Audio notification unavailable:', e);
            }
        }

        // 🔔 Show Toast Notification UI
        function showNotificationToast(item) {
            const container = document.getElementById('notification-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-slate-900 text-white p-4 rounded-2xl shadow-2xl border border-slate-700 flex items-start gap-3 transform transition-all duration-300 translate-y-2 opacity-0';

            toast.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-sm shrink-0 border border-sky-400/30">
                    🔔
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-sky-300 leading-tight">${item.title}</p>
                    <p class="text-xs text-slate-200 font-normal mt-0.5 line-clamp-2">${item.message}</p>
                    ${item.url ? `<a href="${item.url}" class="inline-block mt-2 text-[11px] font-bold text-sky-400 hover:text-sky-300 underline">Buka Halaman →</a>` : ''}
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white p-1 text-xs">✕</button>
            `;

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            // Play chime sound
            playNotificationChime();

            // Auto dismiss after 7 seconds
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 7000);
        }

        // 🔄 Realtime Polling Engine (Every 6 Seconds)
        (function initRealtimeSync() {
            let lastTimestamp = localStorage.getItem('jadibisa_last_notif_time') || new Date(Date.now() - 30000).toISOString();
            const seenIds = new Set(JSON.parse(sessionStorage.getItem('jadibisa_seen_notifs') || '[]'));

            async function pollUpdates() {
                try {
                    const res = await fetch(`{{ url('api/notifications/poll') }}?since=${encodeURIComponent(lastTimestamp)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) return;
                    const data = await res.parse ? await res.parse() : await res.json();

                    if (data && data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(item => {
                            if (!seenIds.has(item.id)) {
                                seenIds.add(item.id);
                                showNotificationToast(item);
                            }
                        });
                        sessionStorage.setItem('jadibisa_seen_notifs', JSON.stringify(Array.from(seenIds)));
                    }

                    if (data && data.timestamp) {
                        lastTimestamp = data.timestamp;
                        localStorage.setItem('jadibisa_last_notif_time', lastTimestamp);
                    }
                } catch (err) {
                    // Fail silently on network drop
                }
            }

            // Initial poll and recurring interval
            setTimeout(pollUpdates, 2000);
            setInterval(pollUpdates, 6000);
        })();
    </script>
</body>
</html>
