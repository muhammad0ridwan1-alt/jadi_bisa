<x-app-layout>
    <x-slot name="header">
        Kelola Kelas — {{ $mataKuliah->name }}
    </x-slot>

    <!-- Page Header & Back Button -->
    <div data-aos="fade-down" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('dosen.dashboard') }}" class="flex items-center gap-2 text-slate-600 hover:text-sky-700 bg-white hover:bg-sky-50 border border-slate-200 hover:border-sky-200 px-4 py-2.5 rounded-xl text-sm font-medium transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-sky-100 text-sky-700">
                        {{ $mataKuliah->kode_mk }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">{{ $mataKuliah->jurusan }}</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $mataKuliah->name }}</h2>
            </div>
        </div>

        <a href="{{ route('dosen.kelas.export_nilai', $mataKuliah->id) }}" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 border border-emerald-400/30 active:scale-95 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Ekspor Nilai (CSV)</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ editModulData: null }">
        <!-- Main Section: List Modul & Tugas -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Modul Section -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-6">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Daftar Modul & Kuis</h3>
                    <span class="text-xs font-bold bg-sky-50 text-sky-600 px-3 py-1 rounded-full border border-sky-100">
                        {{ $mataKuliah->moduls->count() }} Modul Total
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($mataKuliah->moduls as $modul)
                        <div class="p-5 rounded-2xl border border-slate-100 hover:border-sky-200 hover:shadow-md transition-all flex items-start justify-between gap-4 group bg-white">
                            <div class="space-y-2 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $modul->type === 'kuis' ? 'bg-amber-100 text-amber-700' : 'bg-sky-100 text-sky-700' }}">
                                        {{ ucfirst($modul->type) }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $modul->created_at->format('d M Y') }}</span>
                                </div>
                                <h4 class="font-bold text-slate-900 text-lg group-hover:text-sky-700 transition-colors truncate">{{ $modul->title }}</h4>
                                <p class="text-sm text-slate-500 line-clamp-2">{{ $modul->description }}</p>
                                
                                <div class="flex items-center gap-4 text-xs font-medium text-slate-500 pt-1">
                                    @if($modul->file_path)
                                        <span class="text-emerald-600 font-bold flex items-center gap-1">PDF Terlampir</span>
                                    @endif
                                    @if($modul->youtube_link)
                                        <span class="text-red-600 font-bold flex items-center gap-1">Video YouTube</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions for Dosen: Edit & Delete -->
                            <div class="flex items-center gap-2 shrink-0">
                                @if($modul->type === 'kuis')
                                    <a href="{{ route('dosen.modul.penilaian', $modul->id) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl border border-amber-200 transition-colors">
                                        Nilai Kuis
                                    </a>
                                @endif

                                <form action="{{ route('dosen.modul.destroy', $modul->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus Modul">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-sm italic">
                            Belum ada modul di dalam kelas ini. Silakan unggah modul baru menggunakan form di samping.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tugas Praktikum Section -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-6">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Tugas Praktikum Kelas</h3>
                    <span class="text-xs font-bold bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full border border-indigo-100">
                        {{ $mataKuliah->tugases->count() }} Tugas Active
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($mataKuliah->tugases as $tugas)
                        <div class="p-5 rounded-2xl border border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-slate-900 text-base">{{ $tugas->title }}</h4>
                                <p class="text-xs text-rose-600 font-semibold mt-1">Deadline: {{ $tugas->deadline->format('d M Y, H:i') }} WIB</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $tugas->submissions->count() }} Mahasiswa Mengumpulkan</p>
                            </div>
                            <form action="{{ route('dosen.tugas.destroy', $tugas->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada tugas praktikum yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Forms: Add Modul & Add Tugas -->
        <div class="space-y-6">
            <!-- Form Add Modul -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-900 text-base">Tambah Modul Materi / Kuis</h3>
                <form action="{{ route('dosen.modul.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Modul</label>
                        <input type="text" name="title" required placeholder="Contoh: Pengenalan Dasar 1" class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Modul</label>
                        <select name="type" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 bg-white">
                            <option value="materi">Materi Pembelajaran</option>
                            <option value="kuis">Kuis Evaluasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Catatan</label>
                        <textarea name="description" rows="3" placeholder="Ringkasan penjelasan modul..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">File Dokumen (PDF)</label>
                        <input type="file" name="file_path" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Link Video YouTube</label>
                        <input type="url" name="youtube_link" placeholder="https://www.youtube.com/watch?v=..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600">
                    </div>

                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition-colors">
                        Upload Modul Baru
                    </button>
                </form>
            </div>

            <!-- Form Add Tugas -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-900 text-base">Buat Tugas Praktikum</h3>
                <form action="{{ route('dosen.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Tugas</label>
                        <input type="text" name="title" required placeholder="Contoh: Praktikum 1 Excel" class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Batas Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" required class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Instruksi Tugas</label>
                        <textarea name="description" rows="3" placeholder="Instruksi pengerjaan..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">File Soal / Template (Opsional)</label>
                        <input type="file" name="file_path" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition-colors">
                        Publikasikan Tugas
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
