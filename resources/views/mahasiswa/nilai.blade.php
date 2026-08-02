<x-app-layout>
    <x-slot name="header">
        Laporan Nilai Kuis
    </x-slot>

    <div class="mb-4 sm:mb-6">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Nilai Anda</h2>
        <p class="text-slate-500 text-xs sm:text-sm">Berikut adalah daftar nilai dari kuis-kuis yang telah Anda selesaikan.</p>
    </div>

    <!-- Mobile Card View (shown on small screens) -->
    <div class="sm:hidden space-y-3 mb-16">
        @forelse($nilais as $nilai)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-2">
                    <div class="space-y-1 min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ $nilai->modul->mataKuliah->name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ $nilai->modul->title }}</p>
                    </div>
                    @if($nilai->status === 'graded')
                        <span class="px-2.5 py-1 font-bold text-sky-700 bg-sky-100 rounded-lg text-sm shrink-0 ml-2">{{ $nilai->score }}</span>
                    @else
                        <span class="px-2.5 py-1 font-medium text-amber-700 bg-amber-100 rounded-lg text-[11px] shrink-0 ml-2">Menunggu</span>
                    @endif
                </div>
                <p class="text-[10px] text-slate-400">{{ $nilai->updated_at->translatedFormat('d F Y') }}</p>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-300">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p class="text-slate-500 font-medium text-sm">Anda belum mengumpulkan kuis apapun.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View (hidden on small screens) -->
    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul Kuis</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Selesai</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($nilais as $nilai)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-medium text-slate-900">{{ $nilai->modul->mataKuliah->name }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $nilai->modul->title }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $nilai->updated_at->translatedFormat('d F Y') }}</td>
                            <td class="py-4 px-6 text-right">
                                @if($nilai->status === 'graded')
                                    <span class="px-3 py-1 font-bold text-sky-700 bg-sky-100 rounded-lg">{{ $nilai->score }}</span>
                                @else
                                    <span class="px-3 py-1 font-medium text-amber-700 bg-amber-100 rounded-lg text-xs">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-slate-500">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Anda belum mengumpulkan kuis apapun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
