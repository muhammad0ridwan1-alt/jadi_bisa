<x-app-layout>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Welcome Hero Banner -->
    <div data-aos="fade-down" class="mb-6 sm:mb-8 p-6 sm:p-8 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-sky-50 text-sky-700 rounded-md text-xs font-bold border border-sky-200">
                Administrator System Control
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Selamat Datang, Admin</h2>
            <p class="text-slate-600 text-xs sm:text-sm font-normal leading-relaxed">
                Kelola seluruh data pengguna, peran akses dosen & mahasiswa, serta publikasikan pengumuman akademik.
            </p>
        </div>
    </div>

    <!-- Overview Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <div data-aos="fade-up" data-aos-delay="0" class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider">Total User</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900">{{ \App\Models\User::count() }}</p>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="50" class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider">Mahasiswa</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900">{{ \App\Models\User::where('role', 'mahasiswa')->count() }}</p>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider">Dosen Pengampu</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900">{{ \App\Models\User::where('role', 'dosen')->count() }}</p>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="150" class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider">Mata Kuliah</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900">{{ \App\Models\MataKuliah::count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Panels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-16 md:mb-0">
        <div data-aos="fade-right" class="bg-white p-5 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg">Kelola Pengguna</h3>
            <p class="text-xs text-slate-500">Lihat dan hapus akun mahasiswa atau dosen yang terdaftar di platform.</p>
            <div class="flex gap-3">
                <a href="{{ route('admin.users', 'mahasiswa') }}" class="flex-1 text-center py-2.5 bg-sky-50 text-sky-700 font-bold rounded-xl text-xs hover:bg-sky-100 transition-colors border border-sky-200 active:scale-95">
                    Kelola Mahasiswa
                </a>
                <a href="{{ route('admin.users', 'dosen') }}" class="flex-1 text-center py-2.5 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-xs hover:bg-emerald-100 transition-colors border border-emerald-200 active:scale-95">
                    Kelola Dosen
                </a>
            </div>
        </div>

        <div data-aos="fade-left" class="bg-white p-5 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg">Publikasi Informasi</h3>
            <p class="text-xs text-slate-500">Buat pengumuman official ke seluruh mahasiswa atau per jurusan tertentu.</p>
            <a href="{{ route('admin.pengumuman') }}" class="block w-full text-center py-2.5 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-slate-800 transition-colors active:scale-95">
                Buka Pengumuman Admin
            </a>
        </div>
    </div>
</x-app-layout>
