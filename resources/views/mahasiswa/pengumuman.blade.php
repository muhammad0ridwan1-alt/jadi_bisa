<x-app-layout>
    <x-slot name="header">
        Pengumuman Official BEC
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Pengumuman Kampus</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Informasi resmi dari pihak Manajemen Akademik dan Dosen Pengampu.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Pengumuman Aktif</span>
                    <span class="block text-2xl font-black text-white">{{ $pengumumans->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement List Cards -->
    <div class="space-y-4 sm:space-y-6 max-w-5xl mb-20 md:mb-6">
        @forelse($pengumumans as $p)
            <div data-aos="fade-up" class="bg-white rounded-2xl p-5 sm:p-7 shadow-sm border border-slate-200/80 relative overflow-hidden space-y-4 transition-all hover:shadow-md">
                @if($p->is_pinned)
                    <div class="absolute top-0 right-0 bg-sky-600 text-white text-[10px] font-black px-3 py-1 rounded-bl-xl shadow-xs tracking-wider uppercase">
                        📌 PINNED ANNOUNCEMENT
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-2.5 text-xs">
                    <span class="font-extrabold text-sky-700 bg-sky-50 px-3 py-1 rounded-full border border-sky-200/60 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        {{ $p->author->name }} ({{ ucfirst($p->author->role) }})
                    </span>
                    <span class="text-slate-300">•</span>
                    <span class="font-mono text-slate-400 font-medium">{{ $p->created_at->diffForHumans() }}</span>
                    @if($p->target && $p->target !== 'semua')
                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 font-bold rounded-md text-[11px]">
                            Target: {{ $p->target }}
                        </span>
                    @endif
                </div>

                <h2 class="text-lg sm:text-2xl font-black text-slate-900 leading-snug">{{ $p->title }}</h2>

                <div class="prose max-w-none text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    {!! nl2br(e($p->content)) !!}
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 space-y-2">
                <p class="text-slate-500 font-bold text-sm">Belum ada pengumuman terbaru.</p>
                <p class="text-xs text-slate-400">Informasi dari kampus dan dosen akan ditampilkan di sini.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
