<x-app-layout>
    <x-slot name="header">
        Mahasiswa — Administrasi Perkantoran
    </x-slot>

    <!-- Header Banner (compact on mobile) -->
    <div data-aos="fade-down" class="mb-4 sm:mb-8 p-4 sm:p-8 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1.5 sm:space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 bg-sky-50 text-sky-700 rounded-md text-[11px] sm:text-xs font-bold border border-sky-200">
                        Administrasi Perkantoran
                    </span>
                    <span class="text-[11px] sm:text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md font-bold">Semester 2</span>
                </div>
                <h2 class="text-xl sm:text-3xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->name }}</h2>
                <p class="text-slate-600 text-xs sm:text-sm font-normal leading-relaxed">
                    Pilih Mata Kuliah di bawah untuk membuka modul materi, kuis evaluasi, dan tugas praktikum.
                </p>
            </div>

            <div class="hidden sm:block bg-slate-50 p-4 rounded-xl border border-slate-200 text-center shrink-0 space-y-1">
                <span class="block text-xs font-bold text-slate-500 uppercase">Performa</span>
                <span class="block text-2xl font-bold text-sky-600">IPK 3.85</span>
                <span class="inline-block text-[11px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-bold">Sangat Memuaskan</span>
            </div>
        </div>
    </div>

    <!-- Blind System Typing Feature Banner -->
    <div data-aos="fade-up" class="mb-4 sm:mb-8 p-4 sm:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-slate-900 via-sky-950 to-slate-900 text-white shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-sky-500/20 text-sky-300 text-xs font-bold border border-sky-400/30">
                Mata Kuliah Spesial BEC
            </div>
            <h3 class="text-lg sm:text-xl font-extrabold">Latihan Ketik 10 Jari (Blind System Typing)</h3>
            <p class="text-xs sm:text-sm text-slate-300">Asah kecepatan (WPM) & akurasi ketik tanpa melihat keyboard untuk persiapan ujian kampus.</p>
        </div>
        <a href="{{ route('mahasiswa.typing') }}" class="shrink-0 w-full sm:w-auto text-center px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs sm:text-sm shadow-md transition-all active:scale-95">
            Mulai Tes Ketik
        </a>
    </div>

    <!-- Section Title -->
    <div class="flex justify-between items-center mb-4 sm:mb-6" data-aos="fade-right">
        <div>
            <h3 class="text-lg sm:text-xl font-bold text-slate-900">Daftar Mata Kuliah</h3>
            <p class="text-[11px] sm:text-xs text-slate-500">Ketuk card untuk membuka modul & kuis</p>
        </div>
        <span class="text-[11px] sm:text-xs font-bold text-slate-600 bg-white px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg border border-slate-200">
            {{ $mataKuliahs->count() }} Matkul
        </span>
    </div>

    <!-- Mata Kuliah Cards Grid (1 col on mobile, 2 on tablet, 3 on desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-16 md:mb-0">
        @forelse($mataKuliahs as $index => $mk)
            <a href="{{ route('mahasiswa.matkul.show', $mk->id) }}" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}" class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md border border-slate-200/80 overflow-hidden hover:border-sky-300 transition-all duration-200 group flex flex-col justify-between active:scale-[0.98]">
                <div>
                    <!-- Header Card -->
                    <div class="p-4 sm:p-6 bg-slate-900 text-white space-y-1.5 sm:space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-0.5 bg-white/10 text-sky-300 text-[11px] sm:text-xs font-bold rounded">
                                {{ $mk->kode_mk }}
                            </span>
                            <span class="text-[10px] sm:text-xs text-slate-400">AP</span>
                        </div>
                        <h4 class="font-bold text-base sm:text-lg text-white group-hover:text-sky-400 transition-colors leading-snug truncate">
                            {{ $mk->name }}
                        </h4>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="flex items-center gap-2.5 text-sm text-slate-600">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ strtoupper(substr($mk->dosen->name ?? 'D', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] sm:text-xs text-slate-400 font-semibold">Dosen Pengampu</p>
                                <p class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $mk->dosen->name ?? 'Belum Ada' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-center">
                            <div class="bg-slate-50 p-2 sm:p-2.5 rounded-lg sm:rounded-xl border border-slate-100">
                                <span class="block text-[10px] sm:text-xs text-slate-400 font-medium">Modul</span>
                                <span class="block text-sm sm:text-base font-bold text-sky-600">{{ $mk->moduls_count ?? $mk->moduls->count() }}</span>
                            </div>
                            <div class="bg-slate-50 p-2 sm:p-2.5 rounded-lg sm:rounded-xl border border-slate-100">
                                <span class="block text-[10px] sm:text-xs text-slate-400 font-medium">Tugas</span>
                                <span class="block text-sm sm:text-base font-bold text-indigo-600">{{ $mk->tugases_count ?? $mk->tugases->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-4 sm:px-6 py-3 bg-slate-50 group-hover:bg-sky-600 transition-colors border-t border-slate-100 flex justify-between items-center text-[11px] sm:text-xs font-bold text-slate-700 group-hover:text-white">
                    <span>Buka Modul & Kuis</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 sm:py-16 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 font-semibold text-sm">Belum ada Mata Kuliah yang tersedia.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
