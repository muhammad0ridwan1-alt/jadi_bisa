<x-app-layout>
    <x-slot name="header">
        {{ $mataKuliah->name }}
    </x-slot>

    <!-- Page Header & Back Button -->
    <div data-aos="fade-down" class="mb-4 sm:mb-8">
        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-sky-700 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-200 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium transition-all shadow-sm active:scale-95 mb-3 sm:mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>

        <!-- Header Card -->
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 text-[11px] sm:text-xs font-bold rounded-full bg-sky-100 text-sky-700">
                            {{ $mataKuliah->kode_mk }}
                        </span>
                        <span class="text-[11px] sm:text-xs text-slate-500 font-medium">{{ $mataKuliah->jurusan }}</span>
                    </div>
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-900">{{ $mataKuliah->name }}</h2>
                </div>
                <div class="flex items-center gap-2.5 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 shrink-0">
                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr($mataKuliah->dosen->name ?? 'D', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium">Dosen Pengampu</p>
                        <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $mataKuliah->dosen->name ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div x-data="{ activeTab: 'modul' }" class="space-y-4 sm:space-y-6 mb-16 md:mb-0">
        <div class="flex bg-white rounded-xl p-1 shadow-sm border border-slate-200 max-w-sm">
            <button @click="activeTab = 'modul'" :class="activeTab === 'modul' ? 'bg-sky-600 text-white font-bold shadow-md' : 'text-slate-600 hover:text-slate-900 font-medium'" class="flex-1 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm transition-all text-center active:scale-95">
                Modul ({{ $mataKuliah->moduls->count() }})
            </button>
            <button @click="activeTab = 'tugas'" :class="activeTab === 'tugas' ? 'bg-sky-600 text-white font-bold shadow-md' : 'text-slate-600 hover:text-slate-900 font-medium'" class="flex-1 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm transition-all text-center active:scale-95">
                Tugas ({{ $mataKuliah->tugases->count() }})
            </button>
        </div>

        <!-- Tab 1: Modul & Kuis -->
        <div x-show="activeTab === 'modul'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($mataKuliah->moduls as $index => $modul)
                <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}" class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                    <div>
                        <div class="flex justify-between items-start mb-3 sm:mb-4">
                            <span class="px-2.5 py-0.5 text-[11px] sm:text-xs font-bold rounded-full {{ $modul->type === 'kuis' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-sky-100 text-sky-700 border border-sky-200' }}">
                                {{ $modul->type === 'kuis' ? 'Kuis Evaluasi' : 'Materi Perkuliahan' }}
                            </span>
                            <span class="text-[10px] sm:text-xs text-slate-400 font-medium">{{ $modul->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <h3 class="font-bold text-slate-900 mb-2 text-base sm:text-lg group-hover:text-sky-700 transition-colors line-clamp-2">{{ $modul->title }}</h3>
                        <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 sm:line-clamp-3 mb-4 sm:mb-6">{{ $modul->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
                    </div>

                    <a href="{{ route('mahasiswa.modul.show', $modul->id) }}" class="flex items-center justify-center gap-2 w-full bg-slate-50 text-slate-700 font-bold py-2.5 rounded-xl border border-slate-200 group-hover:bg-sky-600 group-hover:text-white group-hover:border-sky-600 transition-all duration-300 text-xs sm:text-sm active:scale-95">
                        <span>{{ $modul->type === 'kuis' ? 'Kerjakan Kuis' : 'Buka Materi' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-10 sm:py-12 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <p class="text-slate-500 font-medium text-sm">Belum ada modul atau kuis di dalam mata kuliah ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Tab 2: Tugas -->
        <div x-show="activeTab === 'tugas'" class="space-y-3 sm:space-y-4">
            @forelse($mataKuliah->tugases as $tugas)
                <div class="bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 flex flex-col gap-3 sm:gap-4">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 text-[11px] sm:text-xs font-bold rounded-full bg-indigo-100 text-indigo-700">Tugas Praktikum</span>
                            <span class="text-[11px] sm:text-xs text-rose-600 font-semibold">Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}</span>
                        </div>
                        <h4 class="text-base sm:text-lg font-bold text-slate-900">{{ $tugas->title }}</h4>
                        <p class="text-xs sm:text-sm text-slate-500 line-clamp-2">{{ $tugas->description }}</p>
                    </div>
                    <a href="{{ route('mahasiswa.tugas') }}" class="w-full sm:w-auto sm:self-end bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition-all text-center active:scale-95">
                        Lihat Status Tugas
                    </a>
                </div>
            @empty
                <div class="text-center py-10 sm:py-12 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-500 font-medium text-sm">Belum ada tugas praktikum untuk mata kuliah ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
