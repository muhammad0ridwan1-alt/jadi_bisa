<x-app-layout>
    <x-slot name="header">
        Modul & Materi Perkuliahan (Dosen)
    </x-slot>

    <div x-data="{ addModal: false, selectedMk: '{{ $mataKuliahs->first()->id ?? '' }}' }" class="space-y-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Modul & Materi Perkuliahan</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Unggah modul materi PDF, sematkan video pembelajaran YouTube, atau buat kuis evaluasi.
                    </p>
                </div>
                <button @click="addModal = true" class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Modul / Kuis</span>
                </button>
            </div>
        </div>

        <!-- Modul List by Mata Kuliah -->
        <div class="space-y-6">
            @forelse($mataKuliahs as $mk)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-sky-100 text-sky-800 text-xs font-black rounded-lg">
                                {{ $mk->kode_mk }}
                            </span>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">{{ $mk->name }}</h3>
                                <p class="text-xs text-slate-500">{{ $mk->jurusan }} — Cawu {{ $mk->cawu }}</p>
                            </div>
                        </div>
                        <a href="{{ route('dosen.kelas.show', $mk->id) }}" class="text-xs font-bold text-sky-600 hover:text-sky-700">
                            Buka Detail Kelas →
                        </a>
                    </div>

                    <div class="p-5">
                        @php
                            $mkModuls = $moduls->where('mata_kuliah_id', $mk->id);
                        @endphp

                        @if($mkModuls->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($mkModuls as $modul)
                                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 flex flex-col justify-between">
                                        <div class="space-y-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $modul->type === 'kuis' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                    {{ $modul->type === 'kuis' ? 'Kuis Evaluasi' : 'Materi Pembelajaran' }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-medium">{{ $modul->created_at->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $modul->title }}</h4>
                                            @if($modul->description)
                                                <p class="text-xs text-slate-600 leading-relaxed">{{ Str::limit($modul->description, 100) }}</p>
                                            @endif
                                        </div>

                                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2 text-xs">
                                                @if($modul->file_path)
                                                    <a href="{{ asset('storage/' . $modul->file_path) }}" target="_blank" class="font-bold text-sky-600 hover:underline flex items-center gap-1">
                                                        <span>📄 PDF</span>
                                                    </a>
                                                @endif
                                                @if($modul->youtube_link)
                                                    <span class="font-bold text-rose-600 flex items-center gap-1">
                                                        <span>🎥 Video</span>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                @if($modul->type === 'kuis')
                                                    <a href="{{ route('dosen.modul.penilaian', $modul->id) }}" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-lg text-xs">
                                                        Beri Nilai
                                                    </a>
                                                @endif
                                                <form action="{{ route('dosen.modul.destroy', $modul->id) }}" method="POST" onsubmit="return confirm('Hapus modul ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-slate-400 font-medium text-xs">
                                Belum ada modul atau materi untuk mata kuliah ini. Klik tombol "+ Tambah Modul" di atas.
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 text-slate-500">
                    Belum ada mata kuliah yang diampu.
                </div>
            @endforelse
        </div>

        <!-- MODAL TAMBAH MODUL -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Tambah Modul / Kuis Baru</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('dosen.modul.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mata Kuliah:</label>
                        <select name="mata_kuliah_id" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode_mk }} — {{ $mk->name }} ({{ $mk->jurusan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Judul Modul / Pertemuan:</label>
                        <input type="text" name="title" placeholder="contoh: Pertemuan 1: Dasar Administrasi" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Modul:</label>
                        <select name="type" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                            <option value="materi">Materi Pembelajaran (PDF / Video)</option>
                            <option value="kuis">Kuis / Latihan Evaluasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Deskripsi / Petunjuk:</label>
                        <textarea name="description" rows="3" placeholder="Instruksi perkuliahan untuk mahasiswa..." class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-normal focus:ring-2 focus:ring-sky-600"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Upload Berkas PDF (Opsional):</label>
                        <input type="file" name="file_path" accept=".pdf" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Link Video YouTube (Opsional):</label>
                        <input type="url" name="youtube_link" placeholder="https://www.youtube.com/watch?v=..." class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Publikasikan Modul</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
