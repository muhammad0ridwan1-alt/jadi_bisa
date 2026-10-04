<x-app-layout>
    <x-slot name="header">
        Dashboard Dosen
    </x-slot>

    <!-- Welcome Hero Banner -->
    <div data-aos="fade-down" class="mb-6 sm:mb-8 p-6 sm:p-8 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="space-y-1.5">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Dashboard Dosen</h2>
            <p class="text-slate-600 text-xs sm:text-sm font-normal">
                Kelola materi perkuliahan, kuis evaluasi, penugasan praktikum, rekap nilai, dan jadwal mengajar Anda.
            </p>
        </div>
    </div>

    <!-- Quick Classroom Navigation Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <a href="{{ route('dosen.dashboard') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-sky-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Kelas Saya</span>
                <span class="text-[10px] text-slate-400 font-semibold">{{ $mataKuliahs->count() }} Kelas</span>
            </div>
        </a>

        <a href="{{ route('dosen.modul') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-indigo-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Modul & Materi</span>
                <span class="text-[10px] text-slate-400 font-semibold">{{ $moduls->count() }} Modul</span>
            </div>
        </a>

        <a href="{{ route('dosen.tugas') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-purple-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Tugas Praktikum</span>
                <span class="text-[10px] text-slate-400 font-semibold">Beri Tugas</span>
            </div>
        </a>

        <a href="{{ route('dosen.penilaian') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Penilaian</span>
                <span class="text-[10px] text-slate-400 font-semibold">Rekap & CSV</span>
            </div>
        </a>

        <a href="{{ route('dosen.jadwal') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-amber-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Jadwal Mengajar</span>
                <span class="text-[10px] text-slate-400 font-semibold">Senin - Jumat</span>
            </div>
        </a>

        <a href="{{ route('dosen.mahasiswa') }}" class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md hover:border-teal-300 transition-all flex flex-col items-center text-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <span class="block text-xs font-black text-slate-900">Mahasiswa</span>
                <span class="text-[10px] text-slate-400 font-semibold">Per Kelas Ajar</span>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16 md:mb-0">
        <!-- Main Content: Daftar Kelas Saya -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Kelas / Mata Kuliah Saya</h3>
                <span class="text-xs font-bold bg-white text-slate-600 px-3 py-1 rounded-lg border border-slate-200 shadow-sm">
                    {{ $mataKuliahs->count() }} Kelas
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                @forelse($mataKuliahs as $index => $mk)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden hover:shadow-md hover:border-sky-300 transition-all duration-300 group flex flex-col justify-between">
                        <div>
                            <!-- Header Card Accent -->
                            <div class="p-5 bg-slate-900 text-white space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="px-2.5 py-0.5 bg-white/10 text-sky-300 text-xs font-bold rounded">
                                        {{ $mk->kode_mk }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $mk->jurusan }}</span>
                                </div>
                                <h4 class="text-white font-bold text-lg leading-snug truncate">
                                    {{ $mk->name }}
                                </h4>
                            </div>

                            <div class="p-5 space-y-3 text-center">
                                <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <span class="block text-slate-400 font-medium text-[11px]">Modul</span>
                                        <span class="block text-base font-bold text-slate-900">{{ $mk->moduls->count() }}</span>
                                    </div>
                                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <span class="block text-slate-400 font-medium text-[11px]">Tugas</span>
                                        <span class="block text-base font-bold text-indigo-600">{{ $mk->tugases->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                            <a href="{{ route('dosen.kelas.show', $mk->id) }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 flex items-center gap-1">
                                Kelola Kelas & Modul →
                            </a>
                            <form method="POST" action="{{ route('dosen.kelas.destroy', $mk->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl sm:rounded-2xl p-8 sm:p-12 text-center border border-dashed border-slate-300">
                        <p class="text-slate-500 font-medium text-sm">Anda belum mengampu kelas apapun. Gunakan form di sebelah kanan untuk membuat kelas baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar: Buat Kelas Baru -->
        <div class="space-y-6">
            <div data-aos="fade-left" class="bg-white p-5 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
                <h3 class="font-bold text-slate-900 text-lg">Buat Kelas Baru</h3>
                <p class="text-xs text-slate-500">Mata kuliah baru yang Anda buat akan langsung tersedia untuk mahasiswa di jurusan terkait.</p>
                
                <form method="POST" action="{{ route('dosen.kelas.store') }}" class="space-y-4 pt-2">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Mata Kuliah / Kelas</label>
                        <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600" placeholder="Contoh: Manajemen Keuangan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode Mata Kuliah</label>
                        <input type="text" name="kode_mk" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600" placeholder="Contoh: MK-BM-201">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Program Studi / Jurusan</label>
                        <select name="jurusan" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600 bg-white">
                            <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                            <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition-all active:scale-95">
                        + Buat Kelas Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
