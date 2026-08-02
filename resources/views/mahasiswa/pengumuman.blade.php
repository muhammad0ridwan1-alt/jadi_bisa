<x-app-layout>
    <x-slot name="header">
        Pengumuman Kampus
    </x-slot>

    <div class="mb-4 sm:mb-8" data-aos="fade-down">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Papan Pengumuman Official</h2>
        <p class="text-xs sm:text-sm text-slate-500">Informasi terbaru dari pihak Akademik BEC dan Dosen Pengampu.</p>
    </div>

    <div class="space-y-3 sm:space-y-6 max-w-4xl mb-16 md:mb-0">
        @forelse($pengumumans as $p)
            <div data-aos="fade-up" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 relative overflow-hidden space-y-3 sm:space-y-4">
                @if($p->is_pinned)
                    <div class="absolute top-0 right-0 bg-sky-600 text-white text-[9px] sm:text-[10px] font-extrabold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-bl-xl shadow-sm">
                        PINNED
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs text-slate-400">
                    <span class="font-bold text-sky-600 bg-sky-50 px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full border border-sky-100">
                        {{ $p->author->name }} ({{ ucfirst($p->author->role) }})
                    </span>
                    <span>•</span>
                    <span>{{ $p->created_at->diffForHumans() }}</span>
                </div>

                <h3 class="text-base sm:text-xl font-extrabold text-slate-900">{{ $p->title }}</h3>

                <div class="prose max-w-none text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {!! nl2br(e($p->content)) !!}
                </div>
            </div>
        @empty
            <div class="text-center py-12 sm:py-16 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 font-semibold text-sm">Belum ada pengumuman terbaru.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
