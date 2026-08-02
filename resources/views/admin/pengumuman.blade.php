<x-app-layout>
    <x-slot name="header">
        Admin — Pengumuman Kampus
    </x-slot>

    <div class="mb-4 sm:mb-8" data-aos="fade-down">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Kelola Pengumuman Official</h2>
        <p class="text-xs sm:text-sm text-slate-500">Kirim pengumuman resmi dari pihak Administrator Akademik.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16 md:mb-0">
        <div class="lg:col-span-1 bg-white p-5 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg">Buat Pengumuman</h3>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="title" required placeholder="Contoh: Kalender Akademik Semester Genap" class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Penerima</label>
                    <select name="target" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600 bg-white">
                        <option value="semua">Semua Mahasiswa (Semua Jurusan)</option>
                        <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                        <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Pengumuman</label>
                    <textarea name="content" rows="4" required placeholder="Tulis rincian pesan pengumuman..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_pinned" id="is_pinned" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <label for="is_pinned" class="text-xs text-slate-700 font-medium">Sematkan di Atas (Pinned)</label>
                </div>

                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-sm active:scale-95">
                    Publikasikan Pengumuman
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg">Daftar Pengumuman Terbit</h3>
            @forelse($pengumumans as $p)
                <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-100 shadow-sm space-y-2 flex justify-between items-start">
                    <div class="space-y-1 min-w-0 pr-3">
                        <div class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs">
                            <span class="font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100">Target: {{ $p->target }}</span>
                            <span class="text-slate-400">• Dipublis oleh: {{ $p->author->name }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">{{ $p->title }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($p->content)) !!}</p>
                    </div>
                    <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 p-1">Hapus</button>
                    </form>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-500 text-xs sm:text-sm font-medium">Belum ada pengumuman terbit.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
