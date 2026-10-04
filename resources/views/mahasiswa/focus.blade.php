<x-app-layout>
    <x-slot name="header">
        Ruang Fokus Belajar
    </x-slot>

    <div x-data="focusPomodoro()" x-init="initTimer()" class="max-w-4xl mx-auto space-y-6 mb-20 md:mb-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down" class="text-center space-y-2">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Ruang Belajar Fokus</h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto">
                Tingkatkan konsentrasi belajar modul perkuliahan menggunakan metode Pomodoro (25 menit fokus, 5 menit istirahat).
            </p>
        </div>

        <!-- Pomodoro Main Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-md text-center space-y-8" data-aos="zoom-in">
            <!-- Mode Selector Tabs -->
            <div class="inline-flex p-1.5 bg-slate-100 rounded-2xl border border-slate-200/80 max-w-md w-full">
                <button type="button" @click="setMode('work', 25)" :class="mode === 'work' ? 'bg-sky-600 text-white font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-bold'" class="flex-1 py-2.5 rounded-xl text-xs sm:text-sm transition-all active:scale-95">
                    🎯 Fokus (25m)
                </button>
                <button type="button" @click="setMode('shortBreak', 5)" :class="mode === 'shortBreak' ? 'bg-emerald-600 text-white font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-bold'" class="flex-1 py-2.5 rounded-xl text-xs sm:text-sm transition-all active:scale-95">
                    ☕ Rehat (5m)
                </button>
                <button type="button" @click="setMode('longBreak', 15)" :class="mode === 'longBreak' ? 'bg-purple-600 text-white font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-bold'" class="flex-1 py-2.5 rounded-xl text-xs sm:text-sm transition-all active:scale-95">
                    🌴 Istirahat (15m)
                </button>
            </div>

            <!-- Big Countdown Display -->
            <div class="space-y-3">
                <div class="text-6xl sm:text-8xl font-black font-mono tracking-tight text-slate-900" x-text="formatTime()">
                    25:00
                </div>
                <p class="text-xs sm:text-sm font-bold" :class="mode === 'work' ? 'text-sky-600' : 'text-emerald-600'" x-text="mode === 'work' ? 'Sesi Belajar & Membaca Modul' : 'Waktu Istirahat Singkat'"></p>
            </div>

            <!-- Timer Action Buttons -->
            <div class="flex items-center justify-center gap-3">
                <button type="button" @click="toggleTimer()" :class="isRunning ? 'bg-amber-500 hover:bg-amber-600 text-slate-950' : 'bg-sky-600 hover:bg-sky-700 text-white'" class="px-8 py-3.5 rounded-2xl font-black text-sm sm:text-base shadow-md transition-all active:scale-95 min-w-[140px]">
                    <span x-text="isRunning ? '⏸️ Jeda' : '▶️ Mulai Fokus'"></span>
                </button>
                <button type="button" @click="resetTimer()" class="p-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all active:scale-95" title="Reset Timer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </button>
            </div>

            <!-- Focus Stats -->
            <div class="pt-6 border-t border-slate-100 grid grid-cols-2 max-w-sm mx-auto text-xs">
                <div>
                    <span class="block text-slate-400 font-semibold">Sesi Selesai</span>
                    <span class="text-xl font-black text-slate-900" x-text="sessionsCompleted">0</span>
                </div>
                <div>
                    <span class="block text-slate-400 font-semibold">Total Waktu</span>
                    <span class="text-xl font-black text-sky-600" x-text="(sessionsCompleted * 25) + ' Menit'">0 Menit</span>
                </div>
            </div>
        </div>

        <!-- Accompanying Study Modules Quick Access -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/80 shadow-sm space-y-4" data-aos="fade-up">
            <h3 class="font-extrabold text-slate-900 text-base">Modul Materi Kuliah Terkini</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($moduls->take(4) as $modul)
                    <a href="{{ route('mahasiswa.modul.show', $modul->id) }}" target="_blank" class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-sky-50 hover:border-sky-200 transition-all flex items-center justify-between gap-3 group">
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-sky-600 block">{{ $modul->mataKuliah->name ?? 'Mata Kuliah' }}</span>
                            <span class="text-xs font-bold text-slate-900 truncate block group-hover:text-sky-700">{{ $modul->title }}</span>
                        </div>
                        <span class="text-xs text-slate-400 group-hover:text-sky-600 font-bold shrink-0">Buka ↗</span>
                    </a>
                @empty
                    <p class="text-xs text-slate-400 col-span-2 text-center py-4">Belum ada modul materi perkuliahan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function focusPomodoro() {
            return {
                mode: 'work',
                timeLeft: 25 * 60,
                isRunning: false,
                timerInterval: null,
                sessionsCompleted: 0,

                initTimer() {
                    const saved = localStorage.getItem('jadibisa_focus_sessions');
                    if (saved) this.sessionsCompleted = parseInt(saved) || 0;
                },

                setMode(m, minutes) {
                    this.mode = m;
                    this.pauseTimer();
                    this.timeLeft = minutes * 60;
                },

                toggleTimer() {
                    if (this.isRunning) {
                        this.pauseTimer();
                    } else {
                        this.startTimer();
                    }
                },

                startTimer() {
                    this.isRunning = true;
                    this.timerInterval = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            this.timerFinished();
                        }
                    }, 1000);
                },

                pauseTimer() {
                    this.isRunning = false;
                    clearInterval(this.timerInterval);
                },

                resetTimer() {
                    this.pauseTimer();
                    if (this.mode === 'work') this.timeLeft = 25 * 60;
                    else if (this.mode === 'shortBreak') this.timeLeft = 5 * 60;
                    else if (this.mode === 'longBreak') this.timeLeft = 15 * 60;
                },

                timerFinished() {
                    this.pauseTimer();
                    if (this.mode === 'work') {
                        this.sessionsCompleted++;
                        localStorage.setItem('jadibisa_focus_sessions', this.sessionsCompleted);
                    }

                    // Play chime sound
                    if (typeof playNotificationChime === 'function') {
                        playNotificationChime();
                    }

                    alert(this.mode === 'work' ? '🎉 Sesi fokus 25 menit selesai! Istirahatlah sejenak.' : '⏰ Waktu istirahat selesai! Siap melanjutkan belajar.');
                    this.resetTimer();
                },

                formatTime() {
                    const m = Math.floor(this.timeLeft / 60);
                    const s = this.timeLeft % 60;
                    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                }
            }
        }
    </script>
</x-app-layout>
