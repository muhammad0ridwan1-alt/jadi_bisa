<x-app-layout>
    <x-slot name="header">
        Peringkat & Leaderboard BEC (Batch {{ auth()->user()->angkatan ?? 30 }} - Cawu {{ auth()->user()->cawu ?? 1 }})
    </x-slot>

    <!-- Page Header Banner (Solid High-Contrast Background) -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Peringkat & Leaderboard</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Peringkat prestasi mahasiswa: Akademik (IPK) dan Blind System Typing (BST).
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Cawu Berjalan</span>
                    <span class="block text-2xl font-black text-white">Cawu {{ auth()->user()->cawu ?? 1 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard Tabs Grid -->
    <div x-data="{ activeTab: 'akademik' }" class="space-y-6">
        <!-- Navigation Buttons (Equal Width & Spacious Height Grid) -->
        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 sm:p-2 rounded-2xl border border-slate-200/80 max-w-xl w-full">
            <button type="button" @click="activeTab = 'akademik'" 
                :class="activeTab === 'akademik' ? 'bg-sky-600 text-white shadow-md font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70 font-bold'" 
                class="w-full h-11 sm:h-12 rounded-xl text-xs sm:text-sm md:text-base transition-colors duration-150 flex items-center justify-center gap-2 whitespace-nowrap px-3 sm:px-5">
                <span>🏆 Peringkat IPK Akademik</span>
            </button>
            <button type="button" @click="activeTab = 'bst'" 
                :class="activeTab === 'bst' ? 'bg-sky-600 text-white shadow-md font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70 font-bold'" 
                class="w-full h-11 sm:h-12 rounded-xl text-xs sm:text-sm md:text-base transition-colors duration-150 flex items-center justify-center gap-2 whitespace-nowrap px-3 sm:px-5">
                <span>⌨️ Peringkat BST 10 Jari</span>
            </button>
        </div>

        <!-- 1. AKADEMIK TAB -->
        <div x-show="activeTab === 'akademik'" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 translate-y-1" 
            x-transition:enter-end="opacity-100 translate-y-0" 
            class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Peringkat Prestasi Akademik & IPK</h3>
                    <p class="text-xs text-slate-500">Nilai diakumulasikan secara otomatis dari seluruh Kuis Modul & Tugas Praktikum Dosen.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-sky-100 text-sky-800 rounded-full">Skala IPK 4.00</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/70 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 text-center w-16">Peringkat</th>
                            <th class="py-3.5 px-4">Nama Mahasiswa</th>
                            <th class="py-3.5 px-4">NIM</th>
                            <th class="py-3.5 px-4">Jurusan / Kelas</th>
                            <th class="py-3.5 px-4 text-center">Rata-Rata Nilai</th>
                            <th class="py-3.5 px-4 text-center">IPK Proyeksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($mahasiswas as $index => $mhs)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $mhs->id === auth()->id() ? 'bg-sky-50/70 font-semibold' : '' }}">
                                <td class="py-4 px-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-amber-950 font-black text-sm shadow-md">🥇 1</span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300 text-slate-900 font-black text-sm shadow-md">🥈 2</span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-amber-50 font-black text-sm shadow-md">🥉 3</span>
                                    @else
                                        <span class="text-slate-500 font-bold">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($mhs->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ $mhs->name }}</span>
                                            @if($mhs->id === auth()->id())
                                                <span class="text-[10px] text-sky-600 font-extrabold uppercase">(Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-mono text-slate-600">{{ $mhs->nim ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $mhs->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $mhs->jurusan }} (Kelas {{ $mhs->kelas ?? '3A' }})
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="text-base font-extrabold text-slate-800">{{ $mhs->avg_score }}</span>
                                    <span class="text-[10px] text-slate-400 block">/ 100</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-block px-3 py-1 bg-sky-100 text-sky-900 rounded-xl font-black text-sm">
                                        {{ number_format($mhs->ipk_score, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data nilai mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. BST TYPING TAB -->
        <div x-show="activeTab === 'bst'" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 translate-y-1" 
            x-transition:enter-end="opacity-100 translate-y-0" 
            class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Peringkat Khusus Blind System Typing 10 Jari (BST3)</h3>
                    <p class="text-xs text-slate-500">Murni berdasarkan kecepatan CPM (Karakter Per Menit), WPM, & Akurasi (Terpisah dari IPK Akademik).</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-sky-100 text-sky-800 rounded-full">Target BEC: ≥200 CPM</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/70 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 text-center w-16">Peringkat</th>
                            <th class="py-3.5 px-4">Nama Mahasiswa</th>
                            <th class="py-3.5 px-4">Kelas</th>
                            <th class="py-3.5 px-4 text-center">Skor Utama CPM</th>
                            <th class="py-3.5 px-4 text-center">WPM</th>
                            <th class="py-3.5 px-4 text-center">Akurasi (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($bstRankings as $index => $bst)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ optional($bst->mahasiswa)->id === auth()->id() ? 'bg-sky-50/70 font-semibold' : '' }}">
                                <td class="py-4 px-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-amber-950 font-black text-sm shadow-md">⚡ 1</span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300 text-slate-900 font-black text-sm shadow-md">⚡ 2</span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-amber-50 font-black text-sm shadow-md">⚡ 3</span>
                                    @else
                                        <span class="text-slate-500 font-bold">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr(optional($bst->mahasiswa)->name ?? 'M', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ optional($bst->mahasiswa)->name ?? 'Mahasiswa' }}</span>
                                            @if(optional($bst->mahasiswa)->id === auth()->id())
                                                <span class="text-[10px] text-sky-600 font-extrabold uppercase">(Anda)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-bold">
                                        Kelas {{ optional($bst->mahasiswa)->kelas ?? '3A' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="text-base font-extrabold text-sky-600">{{ $bst->max_cpm }}</span>
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Karakter/Menit</span>
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-emerald-600">
                                    {{ $bst->max_wpm }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold {{ $bst->max_accuracy >= 95 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $bst->max_accuracy }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada skor tes ketik BST yang dicatat. Latihan sekarang di menu Typing!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
