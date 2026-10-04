<x-app-layout>
    <x-slot name="header">
        Simulator Target IPK
    </x-slot>

    @php
        $mkListJson = $mataKuliahs->map(function($mk) {
            return [
                'id' => $mk->id,
                'kode' => $mk->kode_mk,
                'name' => $mk->name,
                'sks' => 3, // Default bobot SKS standar
                'grade' => '4.0', // Default target A
            ];
        })->values();
    @endphp

    <div x-data="gpaSimulator()" x-init="initSimulator()" class="space-y-6 mb-20 md:mb-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Simulator Target IPK</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Simulasikan target perolehan nilai mata kuliah Cawu {{ auth()->user()->cawu ?? 3 }} untuk mencapai target kelulusan impian.
                    </p>
                </div>

                <!-- Live Predicted GPA Badge -->
                <div class="bg-slate-800 p-4 sm:p-5 rounded-2xl border border-slate-700 text-center shrink-0 min-w-[160px] shadow-sm">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Proyeksi IPK Cawu</span>
                    <span class="block text-3xl sm:text-4xl font-black text-white" x-text="predictedGpa">4.00</span>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-black" :class="predicateClass" x-text="predicateText">Cumlaude</span>
                </div>
            </div>
        </div>

        <!-- Simulator Controls & Quick Actions -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-500">Preset Cepat:</span>
                <button type="button" @click="setAllGrades('4.0')" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold rounded-xl text-xs border border-emerald-200 transition-all active:scale-95">
                    🌟 Target Semua A (4.00)
                </button>
                <button type="button" @click="setAllGrades('3.5')" class="px-3 py-1.5 bg-sky-50 text-sky-700 hover:bg-sky-100 font-bold rounded-xl text-xs border border-sky-200 transition-all active:scale-95">
                    Target A/B (3.50)
                </button>
                <button type="button" @click="setAllGrades('3.0')" class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold rounded-xl text-xs border border-slate-200 transition-all active:scale-95">
                    Target Semua B (3.00)
                </button>
            </div>

            <div class="text-xs font-semibold text-slate-500">
                Total <span class="font-bold text-slate-900" x-text="courses.length">0</span> Mata Kuliah Terdaftar
            </div>
        </div>

        <!-- Course Grade Simulator Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="(c, idx) in courses" :key="c.id">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-sky-300 transition-all flex items-center justify-between gap-4">
                    <div class="space-y-1 min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-sky-50 text-sky-700 font-black text-[10px] rounded-md border border-sky-200" x-text="c.kode"></span>
                            <span class="text-[11px] text-slate-400 font-medium">Bobot 3 SKS</span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm truncate" x-text="c.name"></h4>
                    </div>

                    <div class="shrink-0">
                        <label class="block text-[10px] font-bold text-slate-400 mb-1 text-right">Target Nilai:</label>
                        <select x-model="c.grade" @change="calculateGPA()" class="py-1.5 px-3 border border-slate-200 rounded-xl font-black text-sm bg-slate-50 focus:ring-2 focus:ring-sky-600">
                            <option value="4.0">A (4.00) — Sangat Baik</option>
                            <option value="3.5">A- (3.50) — Baik Sekali</option>
                            <option value="3.0">B (3.00) — Baik</option>
                            <option value="2.5">B- (2.50) — Cukup Baik</option>
                            <option value="2.0">C (2.00) — Cukup</option>
                            <option value="1.0">D (1.00) — Kurang</option>
                            <option value="0.0">E (0.00) — Gagal</option>
                        </select>
                    </div>
                </div>
            </template>
        </div>

        <!-- Strategy Advice Box -->
        <div class="bg-sky-50 p-5 rounded-2xl border border-sky-200 space-y-2 text-xs text-sky-900 leading-relaxed font-normal">
            <span class="font-black flex items-center gap-1.5 text-sky-950">
                💡 Rekomendasi Strategi Kelulusan:
            </span>
            <p x-show="predictedGpa >= 3.50">Pertahankan konsistensi mengumpulkan tugas praktikum tepat waktu dan ikuti seluruh kuis evaluasi mingguan untuk mengunci predikat <strong>Pujian / Cumlaude</strong>.</p>
            <p x-show="predictedGpa >= 3.00 && predictedGpa < 3.50">Tingkatkan skor pada kuis-kuis modul dengan target minimal nilai A- (3.50) pada mata kuliah kejuruan utama untuk mendongkrak predikat menjadi Cumlaude.</p>
            <p x-show="predictedGpa < 3.00">Fokuskan pengerjaan revisi tugas praktikum dan ulangi latihan tes ketik 10 jari agar tidak ada mata kuliah di bawah standar minimal kelulusan BEC.</p>
        </div>
    </div>

    <script>
        function gpaSimulator() {
            return {
                courses: {!! json_encode($mkListJson) !!},
                predictedGpa: '4.00',
                predicateText: 'Cumlaude',
                predicateClass: 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30',

                initSimulator() {
                    this.calculateGPA();
                },

                calculateGPA() {
                    if (!this.courses || this.courses.length === 0) {
                        this.predictedGpa = '0.00';
                        return;
                    }

                    let totalPoints = 0;
                    let totalSks = 0;

                    this.courses.forEach(c => {
                        const gradeVal = parseFloat(c.grade) || 0;
                        const sks = c.sks || 3;
                        totalPoints += (gradeVal * sks);
                        totalSks += sks;
                    });

                    const gpa = totalSks > 0 ? (totalPoints / totalSks) : 0;
                    this.predictedGpa = gpa.toFixed(2);

                    if (gpa >= 3.75) {
                        this.predicateText = '🌟 Summa Cumlaude';
                        this.predicateClass = 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30';
                    } else if (gpa >= 3.50) {
                        this.predicateText = '🏆 Magna Cumlaude';
                        this.predicateClass = 'bg-sky-500/20 text-sky-300 border border-sky-400/30';
                    } else if (gpa >= 3.00) {
                        this.predicateText = '✨ Sangat Memuaskan';
                        this.predicateClass = 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30';
                    } else if (gpa >= 2.50) {
                        this.predicateText = 'Memuaskan';
                        this.predicateClass = 'bg-amber-500/20 text-amber-300 border border-amber-400/30';
                    } else {
                        this.predicateText = 'Perlu Peningkatan';
                        this.predicateClass = 'bg-rose-500/20 text-rose-300 border border-rose-400/30';
                    }
                },

                setAllGrades(val) {
                    this.courses.forEach(c => c.grade = val);
                    this.calculateGPA();
                }
            }
        }
    </script>
</x-app-layout>
