<x-app-layout>
    <x-slot name="header">
        Tugas & Praktikum Mahasiswa (Dosen)
    </x-slot>

    <div x-data="{ addModal: false, gradeModal: false, activeSub: { id: '', name: '', score: '', feedback: '' } }" class="space-y-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Tugas & Praktikum</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Buat tugas praktikum terstruktur, tentukan batas waktu deadline, dan periksa berkas pengumpulan tugas mahasiswa.
                    </p>
                </div>
                <button @click="addModal = true" class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Buat Tugas Baru</span>
                </button>
            </div>
        </div>

        <!-- Tugas List -->
        <div class="space-y-6">
            @forelse($tugases as $tugas)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 text-xs font-bold rounded">
                                    {{ $tugas->mataKuliah->kode_mk ?? 'MK' }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium">{{ $tugas->mataKuliah->name ?? '' }}</span>
                            </div>
                            <h3 class="text-base font-extrabold text-slate-900">{{ $tugas->title }}</h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg">
                                ⏰ Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}
                            </span>
                            <form action="{{ route('dosen.tugas.destroy', $tugas->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">{{ $tugas->description }}</p>

                        @if($tugas->file_path)
                            <div class="inline-flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-sky-600">
                                <span>📎 Berkas Soal:</span>
                                <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="hover:underline">Unduh Lampiran Tugas</a>
                            </div>
                        @endif

                        <!-- Submissions Table -->
                        <div class="pt-3 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Pengumpulan Tugas Mahasiswa</h4>
                                <span class="text-xs font-bold text-slate-500">{{ $tugas->submissions->count() }} Mahasiswa Mengumpulkan</span>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-slate-200">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider">
                                        <tr>
                                            <th class="py-2.5 px-4">Mahasiswa</th>
                                            <th class="py-2.5 px-4">Waktu Kumpul</th>
                                            <th class="py-2.5 px-4">Berkas Jawaban</th>
                                            <th class="py-2.5 px-4 text-center">Nilai</th>
                                            <th class="py-2.5 px-4 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($tugas->submissions as $sub)
                                            <tr class="hover:bg-slate-50">
                                                <td class="py-3 px-4">
                                                    <span class="font-bold text-slate-900 block">{{ $sub->mahasiswa->name ?? 'Mahasiswa' }}</span>
                                                    <span class="text-[11px] text-slate-400">Kelas {{ $sub->mahasiswa->kelas ?? '-' }} • NIM: {{ $sub->mahasiswa->nim ?? '-' }}</span>
                                                </td>
                                                <td class="py-3 px-4 text-slate-600">
                                                    {{ $sub->created_at ? $sub->created_at->format('d M Y, H:i') : '-' }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($sub->file_path)
                                                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="font-bold text-sky-600 hover:underline flex items-center gap-1">
                                                            <span>📄 Unduh File</span>
                                                        </a>
                                                    @else
                                                        <span class="text-slate-400 italic">Tanpa file</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    @if($sub->score !== null)
                                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-black text-xs">
                                                            {{ $sub->score }} / 100
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-bold text-[11px]">
                                                            Belum Dinilai
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <button @click="gradeModal = true; activeSub = { id: '{{ $sub->id }}', name: '{{ $sub->mahasiswa->name ?? 'Mahasiswa' }}', score: '{{ $sub->score ?? '' }}', feedback: '{{ addslashes($sub->feedback ?? '') }}' }" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-xs transition-all active:scale-95 shadow-xs">
                                                        Beri Nilai
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-6 text-center text-slate-400 font-medium">Belum ada mahasiswa yang mengumpulkan tugas ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 text-slate-500">
                    Belum ada tugas praktikum yang dibuat. Klik tombol "+ Buat Tugas Baru" di atas.
                </div>
            @endforelse
        </div>

        <!-- MODAL BUAT TUGAS BARU -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Buat Tugas Praktikum Baru</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('dosen.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs sm:text-sm">
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
                        <label class="block font-bold text-slate-700 mb-1">Judul Tugas Praktikum:</label>
                        <input type="text" name="title" placeholder="contoh: Tugas 1: Pembuatan Buku Kas & Jurnal" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Deskripsi & Instruksi Tugas:</label>
                        <textarea name="description" rows="3" required placeholder="Jelaskan detail instruksi praktikum, format pengumpulan file, dll..." class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-normal focus:ring-2 focus:ring-sky-600"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Batas Waktu Pengumpulan (Deadline):</label>
                        <input type="datetime-local" name="deadline" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Lampiran Berkas Soal (Opsional):</label>
                        <input type="file" name="file_path" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs">
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Publikasikan Tugas</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL GRADE TUGAS -->
        <div x-show="gradeModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="gradeModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Penilaian Tugas Mahasiswa</h3>
                    <button @click="gradeModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('dosen/tugas-submission') }}/' + activeSub.id + '/grade'" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Mahasiswa:</p>
                        <p class="text-base font-black text-slate-900" x-text="activeSub.name"></p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nilai (Skala 0 - 100):</label>
                        <input type="number" step="0.1" min="0" max="100" name="score" x-model="activeSub.score" required placeholder="contoh: 90" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-black text-lg focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan / Feedback Dosen:</label>
                        <textarea name="feedback" rows="3" x-model="activeSub.feedback" placeholder="Komentar hasil pengerjaan..." class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-normal focus:ring-2 focus:ring-sky-600"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="gradeModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
