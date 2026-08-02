<x-app-layout>
    <x-slot name="header">
        Jadwal Perkuliahanku
    </x-slot>

    <div class="mb-4 sm:mb-8" data-aos="fade-down">
        <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Jadwal Kuliah Mingguan</h2>
        <p class="text-xs sm:text-sm text-slate-500">Program Studi: <span class="font-bold text-sky-600">{{ auth()->user()->jurusan }}</span></p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-16 md:mb-0">
        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        @endphp

        @foreach($days as $hari)
            @php
                $jadwalHariIni = $jadwals->where('hari', $hari);
            @endphp
            <div data-aos="fade-up" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3 sm:mb-4 pb-2 sm:pb-3 border-b border-slate-100">
                        <h3 class="font-extrabold text-base sm:text-lg text-slate-900">{{ $hari }}</h3>
                        <span class="text-[10px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full font-bold {{ $jadwalHariIni->count() > 0 ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $jadwalHariIni->count() }} Kelas
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($jadwalHariIni as $j)
                            <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-sky-50/60 border border-sky-100 space-y-1.5 sm:space-y-2">
                                <div class="flex flex-wrap items-center justify-between gap-1 text-[11px] sm:text-xs font-bold text-sky-700">
                                    <span>{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB</span>
                                    <span class="bg-white px-1.5 sm:px-2 py-0.5 rounded-md text-slate-700 border border-slate-200 text-[10px] sm:text-xs">{{ $j->ruangan }}</span>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-tight">{{ $j->mataKuliah->name }}</h4>
                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium">Dosen: {{ $j->mataKuliah->dosen->name ?? '-' }}</p>
                            </div>
                        @empty
                            <p class="text-[11px] sm:text-xs text-slate-400 py-4 sm:py-6 text-center italic">Tidak ada jadwal hari ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
