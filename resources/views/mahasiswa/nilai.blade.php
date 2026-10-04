<x-app-layout>
    <x-slot name="header">
        Laporan Nilai & Transkrip Kuis
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Transkrip Nilai</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Rekapitulasi perolehan nilai kuis dan performa akademik mahasiswa.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Proyeksi IPK</span>
                    <span class="block text-2xl font-black text-white">{{ number_format(auth()->user()->ipk, 2) }}</span>
                </div>
                <a href="{{ route('mahasiswa.khs.download') }}" target="_blank" class="px-5 py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-black text-xs rounded-2xl shadow-md transition-all flex items-center gap-2 border border-sky-400/30 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>🖨️ Cetak KHS PDF</span>
                </a>
            </div>
        </div>
    </div>

    @php
        $gradedNilais = $nilais->where('status', 'graded');
        $avgScore = $gradedNilais->count() > 0 ? round($gradedNilais->avg('score'), 1) : 0;
    @endphp

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 mb-6">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-400 uppercase tracking-wider">Kuis Selesai</span>
            <p class="text-xl sm:text-2xl font-black text-slate-900">{{ $gradedNilais->count() }} <span class="text-xs font-normal text-slate-400">Modul</span></p>
        </div>
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-400 uppercase tracking-wider">Rata-Rata Nilai</span>
            <p class="text-xl sm:text-2xl font-black text-sky-600">{{ $avgScore }} <span class="text-xs font-normal text-slate-400">/ 100</span></p>
        </div>
        <div class="col-span-2 sm:col-span-1 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-1">
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-400 uppercase tracking-wider">Status Akademik</span>
            <p class="text-sm sm:text-base font-black text-emerald-600 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Aktif & Baik</span>
            </p>
        </div>
    </div>

    <!-- Mobile Card View (shown on small screens) -->
    <div class="sm:hidden space-y-3 mb-16">
        @forelse($nilais as $nilai)
            <div class="bg-white rounded-2xl p-4 shadow-2xs border border-slate-200/80 space-y-3">
                <div class="flex justify-between items-start">
                    <div class="space-y-1 min-w-0 flex-1">
                        <span class="px-2 py-0.5 bg-sky-50 text-sky-700 font-mono text-[10px] font-bold rounded">
                            {{ $nilai->modul->mataKuliah->kode_mk }}
                        </span>
                        <p class="text-xs font-bold text-slate-900 truncate mt-1">{{ $nilai->modul->mataKuliah->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $nilai->modul->title }}</p>
                    </div>
                    @if($nilai->status === 'graded')
                        <span class="px-3 py-1 font-black text-sm rounded-xl shrink-0 ml-2 {{ $nilai->score >= 85 ? 'bg-emerald-100 text-emerald-900' : ($nilai->score >= 70 ? 'bg-sky-100 text-sky-900' : 'bg-amber-100 text-amber-900') }}">
                            {{ $nilai->score }}
                        </span>
                    @else
                        <span class="px-2.5 py-1 font-bold text-amber-800 bg-amber-100 rounded-lg text-[10px] shrink-0 ml-2">Menunggu</span>
                    @endif
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-400 font-mono">
                    <span>Tanggal: {{ $nilai->updated_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 space-y-2">
                <p class="text-slate-500 font-bold text-sm">Belum ada kuis yang dikerjakan.</p>
                <p class="text-xs text-slate-400">Buka menu Mata Kuliah untuk mulai menyelesai kuis evaluasi.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (hidden on small screens) -->
    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-bold text-[11px] tracking-wider">
                        <th class="py-3.5 px-6">Mata Kuliah</th>
                        <th class="py-3.5 px-6">Judul Kuis Modul</th>
                        <th class="py-3.5 px-6 text-center">Tanggal Selesai</th>
                        <th class="py-3.5 px-6 text-right">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($nilais as $nilai)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2.5">
                                    <span class="px-2 py-0.5 bg-sky-100 text-sky-800 font-mono text-xs font-black rounded">
                                        {{ $nilai->modul->mataKuliah->kode_mk }}
                                    </span>
                                    <span class="font-bold text-slate-900">{{ $nilai->modul->mataKuliah->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-700 font-medium">{{ $nilai->modul->title }}</td>
                            <td class="py-4 px-6 text-center font-mono text-slate-500">{{ $nilai->updated_at->translatedFormat('d M Y, H:i') }} WIB</td>
                            <td class="py-4 px-6 text-right">
                                @if($nilai->status === 'graded')
                                    <span class="inline-block px-3.5 py-1 font-black text-sm rounded-xl {{ $nilai->score >= 85 ? 'bg-emerald-100 text-emerald-900' : ($nilai->score >= 70 ? 'bg-sky-100 text-sky-900' : 'bg-amber-100 text-amber-900') }}">
                                        {{ $nilai->score }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 font-bold text-amber-800 bg-amber-100 rounded-lg text-xs">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-slate-400">
                                Belum ada kuis modul yang Anda selesaikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
