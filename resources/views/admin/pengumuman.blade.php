<x-app-layout>
    <x-slot name="header">
        Publikasi Pengumuman Official (Admin)
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Kelola Pengumuman</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Publikasi pengumuman dan informasi resmi kampus kepada mahasiswa.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Total</span>
                    <span class="block text-2xl font-black text-white">{{ $pengumumans->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <span>✅ {{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-16 md:mb-6">
        <!-- Form Buat Pengumuman -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 space-y-4">
            <h3 class="font-extrabold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-3">Buat Pengumuman Baru</h3>
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Judul Pengumuman:</label>
                    <input type="text" name="title" required placeholder="Contoh: Kalender Akademik Semester Genap" class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600 font-bold">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Target Penerima:</label>
                    <select name="target" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600 bg-white font-medium">
                        <option value="semua">Semua Mahasiswa (Semua Jurusan)</option>
                        <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                        <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Isi Pengumuman:</label>
                    <textarea name="content" rows="5" required placeholder="Tulis rincian pesan pengumuman..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600 font-medium"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_pinned" id="is_pinned" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <label for="is_pinned" class="text-xs text-slate-700 font-bold">Sematkan di Atas (Pinned)</label>
                </div>

                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-extrabold py-3 rounded-xl text-xs sm:text-sm transition-all shadow-sm active:scale-95">
                    📢 Publikasikan Pengumuman
                </button>
            </form>
        </div>

        <!-- Daftar Pengumuman Terbit -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs flex justify-between items-center">
                <h3 class="font-extrabold text-slate-900 text-base">Daftar Pengumuman Terbit</h3>
                <span class="text-xs font-bold text-slate-500">Total: {{ $pengumumans->count() }} Pengumuman</span>
            </div>

            @forelse($pengumumans as $p)
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3 relative overflow-hidden transition-all hover:shadow-xs">
                    @if($p->is_pinned)
                        <span class="absolute top-0 right-0 bg-sky-600 text-white text-[9px] font-black px-2.5 py-0.5 rounded-bl-lg uppercase tracking-wider">PINNED</span>
                    @endif

                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="font-extrabold text-sky-700 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-200">
                            Target: {{ $p->target }}
                        </span>
                        <span class="text-slate-400">• Dipublis oleh: <strong>{{ $p->author->name }}</strong></span>
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-400 font-mono">{{ $p->created_at->diffForHumans() }}</span>
                    </div>

                    <h4 class="font-black text-slate-900 text-base sm:text-lg leading-snug">{{ $p->title }}</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">{!! nl2br(e($p->content)) !!}</p>

                    <div class="pt-2 border-t border-slate-100 flex justify-end">
                        <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold rounded-lg text-xs border border-rose-200 transition-colors">
                                Hapus Pengumuman
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-500 text-xs sm:text-sm font-bold">Belum ada pengumuman terbit.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
