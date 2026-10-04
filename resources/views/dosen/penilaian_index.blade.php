<x-app-layout>
    <x-slot name="header">
        Pusat Penilaian & Rekap Nilai (Dosen)
    </x-slot>

    <div x-data="{ tab: 'kuis', gradeQuizModal: false, gradeTugasModal: false, activeQuiz: { id: '', name: '', score: '' }, activeTugas: { id: '', name: '', score: '', feedback: '' } }" class="space-y-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Penilaian & Rekap Nilai</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Rekapitulasi nilai kuis evaluasi dan tugas praktikum seluruh mahasiswa perkuliahan.
                    </p>
                </div>

                <!-- Export CSV Dropdown / Action -->
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    @foreach($mataKuliahs as $mk)
                        <a href="{{ route('dosen.kelas.export_nilai', $mk->id) }}" class="px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl text-xs border border-slate-700 flex items-center gap-1.5 transition-all shadow-xs">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>CSV {{ $mk->kode_mk }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-2xs flex gap-2">
            <button @click="tab = 'kuis'" :class="tab === 'kuis' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="flex-1 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2">
                <span>📝 Nilai Kuis Evaluasi ({{ $nilais->count() }})</span>
            </button>
            <button @click="tab = 'tugas'" :class="tab === 'tugas' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="flex-1 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-2">
                <span>📑 Nilai Tugas Praktikum ({{ $tugasSubmissions->count() }})</span>
            </button>
        </div>

        <!-- TAB 1: NILAI KUIS -->
        <div x-show="tab === 'kuis'" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-900">Daftar Nilai Kuis Mahasiswa</h3>
                <span class="text-xs font-bold text-slate-500">{{ $nilais->count() }} Data Nilai</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Mahasiswa</th>
                            <th class="py-3.5 px-6">Mata Kuliah</th>
                            <th class="py-3.5 px-6">Kuis / Modul</th>
                            <th class="py-3.5 px-6 text-center">Skor Nilai</th>
                            <th class="py-3.5 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($nilais as $n)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block">{{ $n->mahasiswa->name ?? 'Mahasiswa' }}</span>
                                    <span class="text-xs text-slate-400">Kelas {{ $n->mahasiswa->kelas ?? '-' }} • NIM: {{ $n->mahasiswa->nim ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-sky-100 text-sky-800 rounded-lg text-xs font-bold">
                                        {{ $n->modul->mataKuliah->kode_mk ?? 'MK' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-800">{{ $n->modul->title ?? 'Kuis' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-black text-xs sm:text-sm rounded-lg">
                                        {{ $n->score }} / 100
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <button @click="gradeQuizModal = true; activeQuiz = { id: '{{ $n->id }}', name: '{{ $n->mahasiswa->name ?? 'Mahasiswa' }}', score: '{{ $n->score }}' }" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition-all shadow-2xs">
                                        Edit Nilai
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Belum ada data nilai kuis yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: NILAI TUGAS -->
        <div x-show="tab === 'tugas'" style="display: none;" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-900">Daftar Nilai Tugas Praktikum</h3>
                <span class="text-xs font-bold text-slate-500">{{ $tugasSubmissions->count() }} Submission</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Mahasiswa</th>
                            <th class="py-3.5 px-6">Judul Tugas</th>
                            <th class="py-3.5 px-6">Berkas</th>
                            <th class="py-3.5 px-6 text-center">Nilai</th>
                            <th class="py-3.5 px-6">Feedback Dosen</th>
                            <th class="py-3.5 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($tugasSubmissions as $ts)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-900 block">{{ $ts->mahasiswa->name ?? 'Mahasiswa' }}</span>
                                    <span class="text-xs text-slate-400">Kelas {{ $ts->mahasiswa->kelas ?? '-' }} • NIM: {{ $ts->mahasiswa->nim ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-800">{{ $ts->tugas->title ?? 'Tugas' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($ts->file_path)
                                        <a href="{{ asset('storage/' . $ts->file_path) }}" target="_blank" class="font-bold text-sky-600 hover:underline">
                                            📄 File Jawaban
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">Tanpa berkas</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($ts->score !== null)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-black text-xs sm:text-sm rounded-lg">
                                            {{ $ts->score }} / 100
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-xs rounded">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-xs text-slate-600">{{ $ts->feedback ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <button @click="gradeTugasModal = true; activeTugas = { id: '{{ $ts->id }}', name: '{{ $ts->mahasiswa->name ?? 'Mahasiswa' }}', score: '{{ $ts->score ?? '' }}', feedback: '{{ addslashes($ts->feedback ?? '') }}' }" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition-all shadow-2xs">
                                        {{ $ts->score !== null ? 'Edit Nilai' : 'Beri Nilai' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium">Belum ada tugas yang dikumpulkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL EDIT NILAI KUIS -->
        <div x-show="gradeQuizModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="gradeQuizModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Perbarui Nilai Kuis</h3>
                    <button @click="gradeQuizModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('dosen/nilai') }}/' + activeQuiz.id" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Mahasiswa:</p>
                        <p class="text-base font-black text-slate-900" x-text="activeQuiz.name"></p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Skor Nilai (0 - 100):</label>
                        <input type="number" step="0.1" min="0" max="100" name="score" x-model="activeQuiz.score" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-black text-lg focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="gradeQuizModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT NILAI TUGAS -->
        <div x-show="gradeTugasModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="gradeTugasModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Penilaian Tugas Praktikum</h3>
                    <button @click="gradeTugasModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('dosen/tugas-submission') }}/' + activeTugas.id + '/grade'" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <p class="text-xs font-semibold text-slate-500">Mahasiswa:</p>
                        <p class="text-base font-black text-slate-900" x-text="activeTugas.name"></p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nilai (0 - 100):</label>
                        <input type="number" step="0.1" min="0" max="100" name="score" x-model="activeTugas.score" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-black text-lg focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Feedback / Catatan:</label>
                        <textarea name="feedback" rows="3" x-model="activeTugas.feedback" placeholder="Komentar hasil praktikum..." class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-normal focus:ring-2 focus:ring-sky-600"></textarea>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="gradeTugasModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
