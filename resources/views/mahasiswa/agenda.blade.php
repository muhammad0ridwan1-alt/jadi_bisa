<x-app-layout>
    <x-slot name="header">
        Agenda Kuliah & To-Do
    </x-slot>

    @php
        $daysIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $todayIndo = $daysIndo[date('l')] ?? 'Senin';
        $jadwalHariIni = $jadwals->where('hari', $todayIndo)->sortBy('jam_mulai');
    @endphp

    <div x-data="studentAgenda()" x-init="initAgenda()" class="space-y-6 mb-20 md:mb-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Agenda & To-Do Kuliah</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Pantau jadwal perkuliahan hari ini, batas waktu deadline tugas praktikum, dan to-do list belajar pribadi Anda.
                    </p>
                </div>

                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shrink-0 shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Hari Ini</span>
                    <span class="block text-2xl font-black text-white">{{ $todayIndo }}, {{ date('d M') }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Jadwal Hari Ini & Deadline Tugas Mendatang -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 1. Jadwal Hari Ini -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-base font-extrabold text-slate-900">Jadwal Kuliah Hari Ini ({{ $todayIndo }})</h3>
                        </div>
                        <span class="text-xs font-bold text-slate-500">Kelas {{ auth()->user()->kelas ?? '3A' }}</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($jadwalHariIni as $j)
                            <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="p-2.5 rounded-xl bg-sky-50 text-sky-700 font-mono font-black text-xs shrink-0 border border-sky-100">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $j->mataKuliah->name ?? '-' }}</h4>
                                        <p class="text-xs text-slate-400 font-medium">Dosen: {{ $j->dosen->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-amber-50 text-amber-900 border border-amber-200 rounded-lg text-xs font-black shrink-0">
                                    {{ $j->ruangan ?? 'R. Kelas' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 font-medium text-xs">
                                Tidak ada jadwal perkuliahan untuk hari {{ $todayIndo }}. Selamat beristirahat atau belajar mandiri!
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. Deadline Tugas Mendatang -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="50">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-base font-extrabold text-slate-900">Batas Waktu Pengumpulan Tugas (Deadline)</h3>
                        <a href="{{ route('mahasiswa.tugas') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700">Lihat Semua Tugas →</a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($tugases as $t)
                            @php
                                $sub = $t->submissions->first();
                                $isDue = now()->greaterThan($t->deadline);
                            @endphp
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                                <div class="space-y-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-sky-100 text-sky-800 rounded font-bold text-[10px]">
                                            {{ $t->mataKuliah->kode_mk ?? 'MK' }}
                                        </span>
                                        <h4 class="font-bold text-slate-900 text-sm truncate">{{ $t->title }}</h4>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium">
                                        ⏰ Deadline: {{ \Carbon\Carbon::parse($t->deadline)->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>

                                <div class="shrink-0">
                                    @if($sub)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-lg flex items-center gap-1">
                                            ✓ Sudah Dikumpul
                                        </span>
                                    @elseif($isDue)
                                        <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-lg">
                                            Terlewat
                                        </span>
                                    @else
                                        <a href="{{ route('mahasiswa.tugas') }}" class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition-all shadow-xs">
                                            Kumpul Tugas →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 font-medium text-xs">
                                Tidak ada tugas praktikum aktif saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Col: Interactive To-Do List Pribadi -->
            <div class="space-y-6">
                <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4" data-aos="fade-left">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-extrabold text-slate-900 text-base">To-Do List Pribadi</h3>
                        <span class="text-xs font-bold text-slate-400" x-text="completedCount() + '/' + todos.length + ' Selesai'"></span>
                    </div>

                    <!-- Input New Todo -->
                    <form @submit.prevent="addTodo()" class="flex gap-2">
                        <input type="text" x-model="newTodoText" placeholder="Tambah catatan to-do..." class="flex-1 py-2 px-3 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-sky-600">
                        <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs shrink-0 transition-all active:scale-95">
                            + Tambah
                        </button>
                    </form>

                    <!-- Todo Item List -->
                    <div class="space-y-2 pt-1">
                        <template x-for="(todo, index) in todos" :key="index">
                            <div class="p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-all flex items-center justify-between gap-3" :class="todo.done ? 'bg-slate-50 opacity-60' : 'bg-white'">
                                <label class="flex items-center gap-2.5 min-w-0 cursor-pointer flex-1">
                                    <input type="checkbox" x-model="todo.done" @change="saveTodos()" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4">
                                    <span class="text-xs font-medium text-slate-800 break-words" :class="{ 'line-through text-slate-400': todo.done }" x-text="todo.text"></span>
                                </label>
                                <button type="button" @click="removeTodo(index)" class="text-slate-300 hover:text-rose-500 p-1 text-xs shrink-0">✕</button>
                            </div>
                        </template>

                        <div x-show="todos.length === 0" class="py-6 text-center text-slate-400 text-xs font-medium">
                            Belum ada catatan to-do. Tambahkan tugas belajar Anda di atas!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function studentAgenda() {
            return {
                todos: [],
                newTodoText: '',

                initAgenda() {
                    const saved = localStorage.getItem('jadibisa_student_todos');
                    if (saved) {
                        try {
                            this.todos = JSON.parse(saved);
                        } catch (e) {
                            this.todos = [];
                        }
                    } else {
                        this.todos = [
                            { text: 'Review materi kuis cawu 3', done: false },
                            { text: 'Latihan rutin BST 10 jari target 200 CPM', done: true },
                        ];
                        this.saveTodos();
                    }
                },

                addTodo() {
                    if (!this.newTodoText.trim()) return;
                    this.todos.unshift({ text: this.newTodoText.trim(), done: false });
                    this.newTodoText = '';
                    this.saveTodos();
                },

                removeTodo(idx) {
                    this.todos.splice(idx, 1);
                    this.saveTodos();
                },

                saveTodos() {
                    localStorage.setItem('jadibisa_student_todos', JSON.stringify(this.todos));
                },

                completedCount() {
                    return this.todos.filter(t => t.done).length;
                }
            }
        }
    </script>
</x-app-layout>
