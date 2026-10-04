<x-app-layout>
    <x-slot name="header">
        Tugas & Praktikum
    </x-slot>

    <div class="mb-4 sm:mb-8" data-aos="fade-down">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Daftar Tugas & Praktikum</h2>
        <p class="text-xs sm:text-sm text-slate-500">Kerjakan dan unggah dokumen hasil praktikum sebelum batas waktu deadline.</p>
    </div>

    <div class="space-y-4 sm:space-y-6 mb-16 md:mb-0">
        @forelse($tugases as $tugas)
            @php
                $submisi = $tugas->submissions->where('mahasiswa_id', auth()->id())->first();
                $isOverdue = now()->greaterThan($tugas->deadline) && !$submisi;
                $isNear = now()->diffInHours($tugas->deadline, false) >= 0 && now()->diffInHours($tugas->deadline, false) <= 24 && !$submisi;
            @endphp
            <div data-aos="fade-up" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 space-y-3">
                <!-- Top Info -->
                <div class="space-y-2 sm:space-y-3 mb-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-sky-50 text-sky-700 font-bold text-[11px] sm:text-xs rounded-full border border-sky-200">
                            {{ $tugas->mataKuliah->name }}
                        </span>
                        
                        @if($submisi)
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-extrabold text-[11px] sm:text-xs rounded-full border border-emerald-200">
                                🟢 Sudah Dikumpul
                            </span>
                        @elseif($isOverdue)
                            <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 font-extrabold text-[11px] sm:text-xs rounded-full border border-rose-200 animate-pulse">
                                🔴 Terlewat (Overdue)
                            </span>
                        @elseif($isNear)
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 font-extrabold text-[11px] sm:text-xs rounded-full border border-amber-300">
                                🟡 Deadline Dekat (H-1)
                            </span>
                        @endif

                        <span class="text-[11px] sm:text-xs text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                            Deadline: {{ $tugas->deadline->format('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <h3 class="text-base sm:text-xl font-extrabold text-slate-900">{{ $tugas->title }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $tugas->description }}</p>

                    @if($tugas->file_path)
                        <div class="pt-2">
                            <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-sky-600 bg-sky-50 px-3 py-1.5 rounded-lg border border-sky-200 hover:bg-sky-100 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Unduh / Baca Lampiran Soal Dosen (PDF)
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submission Status Box -->
                <div class="bg-slate-50 p-3 sm:p-5 rounded-xl sm:rounded-2xl border border-slate-200 space-y-3">
                    @if($submisi)
                        <div class="flex justify-between items-center text-[11px] sm:text-xs">
                            <span class="text-slate-500">Status Tugas:</span>
                            @if($submisi->status === 'graded')
                                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 font-bold rounded-full">Sudah Dinilai</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-700 font-bold rounded-full">Dikumpulkan</span>
                            @endif
                        </div>

                        @if($submisi->status === 'graded')
                            <div class="bg-white p-3 rounded-xl border border-emerald-200 text-center">
                                <span class="block text-[10px] sm:text-xs text-slate-400 font-medium">Nilai Akhir</span>
                                <span class="block text-2xl sm:text-3xl font-black text-emerald-600">{{ $submisi->score }} / {{ $tugas->max_score }}</span>
                                @if($submisi->feedback)
                                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1 italic">Catatan: {{ $submisi->feedback }}</p>
                                @endif
                            </div>
                        @else
                            <div class="text-[11px] sm:text-xs text-slate-500 space-y-1">
                                <p class="font-medium text-emerald-600">File Berhasil Terunggah</p>
                                <p class="text-[10px] sm:text-[11px] text-slate-400">Dikumpulkan pada {{ $submisi->created_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    @else
                        <!-- Form Upload Submission -->
                        <form action="{{ route('mahasiswa.tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <label class="block text-[11px] sm:text-xs font-bold text-slate-700">Unggah Berkas Tugas (PDF/DOC/ZIP):</label>
                            <input type="file" name="file_path" required class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                            <textarea name="note" rows="2" placeholder="Catatan opsional..." class="w-full rounded-xl border-slate-200 text-xs focus:ring-sky-600 focus:border-sky-600"></textarea>
                            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-sm active:scale-95">
                                Submit Tugas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 sm:py-16 bg-white rounded-xl sm:rounded-2xl border border-dashed border-slate-300">
                <p class="text-slate-500 font-semibold text-sm">Belum ada tugas praktikum aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
