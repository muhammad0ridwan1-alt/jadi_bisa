<x-app-layout>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Welcome Hero Banner -->
    <div data-aos="fade-down" class="mb-6 sm:mb-8 p-6 sm:p-8 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="space-y-1.5">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Dashboard Admin</h2>
            <p class="text-slate-600 text-xs sm:text-sm font-normal">
                Kelola data pengguna, angkatan, kelas, mata kuliah, jadwal, dan pengumuman.
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 mb-16 md:mb-0">
        <div data-aos="fade-up" data-aos-delay="0" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base mb-1">🎓 Master Angkatan</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tambah Angkatan 30, 31 baru & tetapkan Tahun Ajaran kampus.</p>
            </div>
            <a href="{{ route('admin.angkatan') }}" class="block w-full text-center py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-xs transition-colors shadow-sm active:scale-95">
                Master Angkatan →
            </a>
        </div>

        <div data-aos="fade-up" data-aos-delay="50" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base mb-1">🏢 Master Kelas</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Buat daftar kelas baru per angkatan secara dinamis tanpa hardcode.</p>
            </div>
            <a href="{{ route('admin.kelas_management') }}" class="block w-full text-center py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm active:scale-95">
                Master Kelas →
            </a>
        </div>

        <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base mb-1">📚 Master Matkul</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tambah/edit kurikulum mata kuliah, kode MK, cawu, & Dosen Pengampu.</p>
            </div>
            <a href="{{ route('admin.matkul') }}" class="block w-full text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm active:scale-95">
                Master Matkul →
            </a>
        </div>

        <div data-aos="fade-up" data-aos-delay="150" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base mb-1">📅 Kelola Jadwal</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Atur jadwal sesi perkuliahan per kelas, ruangan, jam, dan angkatan.</p>
            </div>
            <a href="{{ route('admin.jadwal') }}" class="block w-full text-center py-2.5 bg-sky-600 text-white font-bold rounded-xl text-xs hover:bg-sky-700 transition-colors shadow-sm active:scale-95">
                Kelola Jadwal →
            </a>
        </div>

        <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base mb-1">👥 Kelola Pengguna</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Edit data Mahasiswa/Dosen, NIM, cawu berjalan, kelas, & angkatan.</p>
            </div>
            <div class="flex gap-1.5 pt-1">
                <a href="{{ route('admin.users', 'mahasiswa') }}" class="flex-1 text-center py-2 bg-sky-50 text-sky-700 font-bold rounded-xl text-[11px] hover:bg-sky-100 border border-sky-200 active:scale-95">
                    Mahasiswa
                </a>
                <a href="{{ route('admin.users', 'dosen') }}" class="flex-1 text-center py-2 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-[11px] hover:bg-emerald-100 border border-emerald-200 active:scale-95">
                    Dosen
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
