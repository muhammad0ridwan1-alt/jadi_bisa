<x-app-layout>
    <x-slot name="header">
        Jadwal Kelas {{ $kelas }} — Bogor EduCARE
    </x-slot>

    <!-- Page Header Banner (Explicit High-Contrast Executive Banner) -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-sky-600 text-white p-6 sm:p-8 rounded-2xl shadow-lg border border-sky-500 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 relative overflow-hidden" style="background-color: #0284c7; color: #ffffff;">
            <!-- Background Decorative Accent -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="space-y-3 max-w-2xl relative z-10">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('mahasiswa.jadwal') }}" class="inline-flex items-center gap-1.5 text-xs text-white font-black bg-white/20 hover:bg-white/30 px-3.5 py-1.5 rounded-xl border border-white/30 transition-all active:scale-95 shadow-sm">
                        ← Kembali ke Timetable Matrix
                    </a>
                    <span class="text-xs text-white font-black bg-slate-900/40 px-3 py-1 rounded-full border border-white/20">Batch {{ $targetAngkatan ?? auth()->user()->angkatan ?? 30 }} — Cawu {{ str_starts_with($kelas, '1') ? 1 : 3 }}</span>
                    @if(in_array($kelas, ['1G', '1H', '3G', '3H']) || str_contains($kelas, 'BM'))
                        <span class="text-xs font-black bg-amber-400 text-slate-900 px-2.5 py-1 rounded-full shadow-xs">Bisnis Manajemen (BM)</span>
                    @else
                        <span class="text-xs font-black bg-sky-200 text-sky-950 px-2.5 py-1 rounded-full shadow-xs">Administrasi Perkantoran (AP)</span>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <div class="w-14 h-14 rounded-2xl bg-white text-sky-700 font-black flex items-center justify-center text-2xl shadow-xl border-2 border-white shrink-0">
                        {{ $kelas }}
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Jadwal Kuliah Kelas {{ $kelas }}</h1>
                        <p class="text-xs sm:text-sm text-sky-100 font-normal">
                            Jadwal sesi perkuliahan tatap muka resmi Kelas {{ $kelas }}.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Switch Class Navigation Pills (Aligned to the Right / Samping Kanan) -->
            <div class="flex flex-col gap-2 shrink-0 w-full lg:w-auto relative z-10 bg-slate-900/30 p-3 sm:p-4 rounded-2xl border border-white/20 backdrop-blur-md">
                @php
                    $classesToShow = $availableClasses ?? (str_starts_with($kelas, '1') ? ['1A', '1B', '1C', '1D', '1E', '1F', '1G', '1H'] : ['3A', '3B', '3C', '3D', '3E', '3F', '3G', '3H']);
                @endphp
                <span class="text-[11px] font-black text-sky-100 uppercase tracking-wider text-left flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    PINDAH KELAS:
                </span>
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-1.5">
                    @foreach($classesToShow as $c)
                        <a href="{{ route('mahasiswa.jadwal.kelas', $c) }}" 
                           class="px-3 py-2 rounded-xl text-xs font-black transition-all text-center flex items-center justify-center gap-1 border active:scale-95 {{ $c === $kelas ? 'bg-white text-sky-700 shadow-md ring-2 ring-white/50 scale-105' : 'bg-white/20 text-white hover:bg-white/30 border-white/20' }}">
                            <span>{{ $c }}</span>
                            @if(in_array($c, ['1G', '1H', '3G', '3H']))
                                <span class="text-[8px] px-1 py-0.2 rounded bg-amber-500 text-slate-900 font-black shrink-0">BM</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- HIGH-END PROFESSIONAL TIMETABLE VIEW: Structured by Day with Executive Design -->
    <div class="space-y-6 mb-16 md:mb-6" data-aos="fade-up">
        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            $dayColors = [
                'Senin'  => ['bg' => 'bg-sky-600',    'style' => 'background-color: #0284c7;', 'light' => 'bg-sky-50',  'border' => 'border-sky-200', 'text' => 'text-sky-900',  'badge' => 'bg-sky-100 text-sky-900 border-sky-300'],
                'Selasa' => ['bg' => 'bg-indigo-600',  'style' => 'background-color: #4f46e5;', 'light' => 'bg-indigo-50','border' => 'border-indigo-200','text' => 'text-indigo-900','badge' => 'bg-indigo-100 text-indigo-900 border-indigo-300'],
                'Rabu'   => ['bg' => 'bg-slate-800',   'style' => 'background-color: #1e293b;', 'light' => 'bg-slate-50', 'border' => 'border-slate-200','text' => 'text-slate-900', 'badge' => 'bg-slate-200 text-slate-900 border-slate-300'],
                'Kamis'  => ['bg' => 'bg-emerald-600',  'style' => 'background-color: #059669;', 'light' => 'bg-emerald-50','border' => 'border-emerald-200','text' => 'text-emerald-900','badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
                'Jumat'  => ['bg' => 'bg-amber-600',   'style' => 'background-color: #d97706;', 'light' => 'bg-amber-50', 'border' => 'border-amber-200','text' => 'text-amber-900', 'badge' => 'bg-amber-100 text-amber-900 border-amber-300'],
            ];
        @endphp

        @foreach($days as $hari)
            @php
                $jadwalHari = $jadwals->where('hari', $hari)->sortBy('jam_mulai');
                $dc = $dayColors[$hari];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Day Header Banner Strip -->
                <div class="{{ $dc['bg'] }} text-white px-6 py-4 flex items-center justify-between shadow-xs" style="color: #ffffff;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-sm border border-white/30 shadow-xs">
                            {{ strtoupper(substr($hari, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="font-black text-xl uppercase tracking-wider text-white">{{ $hari }}</h3>
                            <p class="text-[11px] text-white/80 font-medium">Hari Perkuliahan Kelas {{ $kelas }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-3.5 py-1.5 bg-white/20 text-white rounded-full border border-white/30 shadow-xs">
                        {{ $jadwalHari->count() }} Sesi Perkuliahan
                    </span>
                </div>

                <!-- Session Items List -->
                @if($jadwalHari->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($jadwalHari as $j)
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 px-6 py-4 hover:bg-slate-50/80 transition-colors">
                                <!-- Left Block: Time & Kode Matkul -->
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="w-28 text-center py-2.5 px-3 {{ $dc['badge'] }} rounded-xl font-mono font-black text-xs border shadow-xs shrink-0">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}
                                        <span class="text-[10px] opacity-70 block font-sans font-bold">s.d.</span>
                                        {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB
                                    </div>

                                    <div class="space-y-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2.5 py-0.5 font-mono font-black text-xs bg-slate-900 text-white rounded-lg shadow-xs">
                                                {{ $j->mataKuliah->kode_mk }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                Ruang {{ $j->ruangan }}
                                            </span>
                                        </div>
                                        <h4 class="font-black text-slate-900 text-base md:text-lg leading-snug truncate">
                                            {{ $j->mataKuliah->name }}
                                        </h4>
                                    </div>
                                </div>

                                <!-- Right Block: Dosen Info & Ruangan Pill -->
                                <div class="flex items-center gap-4 shrink-0 w-full md:w-auto pt-2 md:pt-0 border-t md:border-t-0 border-slate-100 justify-between md:justify-end">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-800 flex items-center justify-center font-black text-sm border border-sky-200 shadow-xs shrink-0">
                                            {{ strtoupper(substr($j->dosen->name ?? 'D', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Dosen Pengampu</p>
                                            <p class="text-xs font-black text-slate-900 truncate max-w-[160px] sm:max-w-[200px]">{{ $j->dosen->name ?? 'Dosen BEC' }}</p>
                                        </div>
                                    </div>

                                    <div class="px-4 py-2 bg-slate-900 text-white rounded-xl font-mono text-xs font-black shadow-sm shrink-0 border border-slate-800">
                                        📍 {{ $j->ruangan }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center text-slate-400 italic text-xs font-medium bg-slate-50/50">
                        ✨ Tidak ada agenda perkuliahan untuk Kelas {{ $kelas }} pada hari {{ $hari }}.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
