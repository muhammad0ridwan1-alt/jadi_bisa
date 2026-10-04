<x-app-layout>
    <x-slot name="header">
        Jadwal Mengajar (Dosen)
    </x-slot>

    <div x-data="{ dayFilter: 'semua' }" class="space-y-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Jadwal Mengajar Mingguan</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Daftar jadwal sesi perkuliahan, ruangan kelas, dan jam mengajar Anda di Bogor EduCARE.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                        <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Total Sesi Mengajar</span>
                        <span class="block text-2xl font-black text-white">{{ $jadwals->count() }} Sesi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Hari Selector -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-wrap gap-1.5">
            @foreach(['semua' => 'Semua Hari', 'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat'] as $key => $label)
                <button @click="dayFilter = '{{ $key }}'" :class="dayFilter === '{{ $key }}' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-2 rounded-xl text-xs font-black transition-all">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <!-- Jadwal Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-900">Tabel Sesi Mengajar</h3>
                <span class="text-xs font-bold text-slate-500">Angkatan {{ \App\Models\Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30 }} • TA {{ \App\Models\Angkatan::where('is_active', true)->value('tahun_ajaran') ?? '2026/2027' }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Hari</th>
                            <th class="py-3.5 px-6">Jam Kuliah</th>
                            <th class="py-3.5 px-6">Kelas</th>
                            <th class="py-3.5 px-6">Mata Kuliah</th>
                            <th class="py-3.5 px-6 text-center">Ruangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($jadwals as $j)
                            <tr x-show="dayFilter === 'semua' || dayFilter === '{{ $j->hari }}'" class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-extrabold">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-sky-100 text-sky-800 font-black rounded-lg text-xs">
                                        Kelas {{ $j->kelas }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">{{ $j->mataKuliah->name ?? '-' }}</div>
                                    <span class="text-xs text-slate-400 font-mono">{{ $j->mataKuliah->kode_mk ?? '' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-200 rounded-lg text-xs font-extrabold">
                                        {{ $j->ruangan ?? 'R. Kelas' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Belum ada jadwal mengajar yang tercatat untuk akun Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
