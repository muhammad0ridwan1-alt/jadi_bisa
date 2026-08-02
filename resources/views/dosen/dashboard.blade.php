<x-app-layout>
    <x-slot name="header">
        Dashboard Dosen
    </x-slot>

    <!-- Welcome Hero Banner -->
    <div data-aos="fade-down" class="mb-6 sm:mb-8 p-6 sm:p-8 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-sm">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-sky-50 text-sky-700 rounded-md text-xs font-bold border border-sky-200">
                Panel Pengajar / Dosen
            </span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-slate-600 text-xs sm:text-sm font-normal leading-relaxed">
                Kelola mata kuliah, unggah materi pembelajaran PDF/video, buat kuis & tugas praktikum, serta berikan penilaian kuis mahasiswa.
            </p>
        </div>
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
