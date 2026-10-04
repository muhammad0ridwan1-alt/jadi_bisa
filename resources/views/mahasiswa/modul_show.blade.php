<x-app-layout>
    <x-slot name="header">
        Materi & Kuis
    </x-slot>

    <!-- Page Header -->
    <div data-aos="fade-down" class="mb-4 sm:mb-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-sky-700 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-200 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-300 shadow-sm active:scale-95 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-1.5">
                <span class="px-2.5 py-0.5 text-[11px] sm:text-xs font-semibold rounded-full {{ $modul->type === 'kuis' ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700' }}">
                    {{ ucfirst($modul->type) }}
                </span>
                <span class="text-[11px] sm:text-sm font-medium text-slate-500">{{ $modul->mataKuliah->name }}</span>
            </div>
            <h2 class="text-lg sm:text-2xl font-bold text-slate-900">{{ $modul->title }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8 mb-16 md:mb-0">
        <!-- Main Content (Video or Description) -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            @if($modul->youtube_link)
                <div data-aos="zoom-in" data-aos-delay="100" class="bg-black rounded-xl sm:rounded-2xl overflow-hidden shadow-sm aspect-video">
                    <iframe 
                        src="{{ $modul->youtube_link }}" 
                        class="w-full h-full"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            @endif

            <div data-aos="fade-up" data-aos-delay="200" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 space-y-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Deskripsi Materi</h3>
                <div class="prose max-w-none text-slate-600 text-sm sm:text-base">
                    {!! nl2br(e($modul->description ?: 'Tidak ada deskripsi yang ditambahkan untuk modul ini.')) !!}
                </div>

                @if($modul->file_path)
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📄 Dokumen Materi PDF:</span>
                        </h4>
                        <div class="w-full h-[500px] bg-slate-900 rounded-xl overflow-hidden shadow-inner border border-slate-200">
                            <iframe src="{{ asset('storage/' . $modul->file_path) }}" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
            @php
                $isCompleted = \App\Models\ModulProgress::where('mahasiswa_id', auth()->id())->where('modul_id', $modul->id)->where('is_completed', true)->exists();
            @endphp

            <!-- Progress Tracking Button -->
            <div data-aos="fade-left" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 space-y-3">
                <h3 class="text-base font-bold text-slate-900">Progress Pembelajaran</h3>
                <form action="{{ route('mahasiswa.modul.toggle_progress', $modul->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-xl font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-xs active:scale-95 {{ $isCompleted ? 'bg-emerald-100 text-emerald-800 border border-emerald-300 hover:bg-emerald-200' : 'bg-sky-600 text-white hover:bg-sky-700' }}">
                        <span>{{ $isCompleted ? '✅ Modul Ini Sudah Selesai' : '⏳ Tandai Selesai Dibaca' }}</span>
                    </button>
                </form>
            </div>

            <div data-aos="fade-left" data-aos-delay="300" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-3 sm:mb-4">Detail Pengajar</h3>
                <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-sky-100 flex items-center justify-center text-sky-600 font-bold text-sm sm:text-lg shrink-0">
                        {{ substr($modul->dosen->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 text-sm sm:text-base truncate">{{ $modul->dosen->name }}</p>
                        <p class="text-xs sm:text-sm text-slate-500">Dosen Pengampu</p>
                    </div>
                </div>

                <hr class="border-slate-100 mb-4 sm:mb-6">

                <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-3 sm:mb-4">File Lampiran</h3>
                @if($modul->file_path)
                    <a href="{{ asset('storage/' . $modul->file_path) }}" target="_blank" class="flex items-center gap-3 w-full bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 font-medium py-3 px-4 rounded-xl transition-colors border border-rose-100 text-sm active:scale-95">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Buka Dokumen PDF (Download)
                    </a>
                @else
                    <div class="text-xs sm:text-sm text-slate-500 flex items-center gap-2 bg-slate-50 py-3 px-4 rounded-xl border border-slate-100">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Tidak ada file lampiran
                    </div>
                @endif
            </div>

            @if($modul->type === 'kuis')
                <div data-aos="fade-up" data-aos-delay="400" class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-md text-white">
                    <h3 class="text-base sm:text-lg font-bold mb-2">Tugas Kuis</h3>
                    
                    @if($nilai && $nilai->status === 'graded')
                        <p class="text-amber-100 text-xs sm:text-sm mb-3 sm:mb-4">Kuis ini telah dinilai oleh Dosen.</p>
                        <div class="bg-white rounded-xl p-3 sm:p-4 text-center">
                            <span class="block text-xs sm:text-sm text-slate-500 mb-1">Nilai Anda</span>
                            <span class="block text-3xl sm:text-4xl font-black text-amber-600">{{ $nilai->score }}</span>
                        </div>
                    @elseif($nilai && $nilai->status === 'submitted')
                        <p class="text-amber-100 text-xs sm:text-sm mb-3 sm:mb-4">Anda telah mengumpulkan kuis ini. Menunggu penilaian dari Dosen.</p>
                        <div class="bg-amber-700/50 rounded-xl p-3 sm:p-4 text-center border border-amber-400/30">
                            <span class="block text-amber-100 font-medium text-sm">Status: Menunggu Nilai</span>
                        </div>
                    @else
                        <p class="text-amber-100 text-xs sm:text-sm mb-4 sm:mb-6">Pastikan Anda telah mempelajari semua materi sebelum mengumpulkan kuis ini.</p>
                        <form action="{{ route('mahasiswa.kuis.submit', $modul->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan dan mengumpulkan kuis ini?');">
                            @csrf
                            <button type="submit" class="w-full bg-white text-amber-600 font-bold py-3 rounded-xl hover:bg-slate-50 transition-colors shadow-sm active:scale-95">
                                Tandai Selesai / Kumpulkan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
