<x-app-layout>
    <x-slot name="header">
        Daftar Mahasiswa (Dosen)
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Direktori Mahasiswa</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Daftar seluruh mahasiswa aktif pada kelas-kelas yang Anda ampu di perkuliahan.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                        <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Total Mahasiswa</span>
                        <span class="block text-2xl font-black text-white">{{ $mahasiswas->count() }} Orang</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Kelas Selector -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-black text-slate-600">Pilih Kelas Ajar:</span>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('dosen.mahasiswa', ['kelas' => 'semua']) }}" class="px-4 py-2 rounded-xl text-xs font-black transition-all border {{ $selectedKelas === 'semua' ? 'bg-sky-600 text-white border-sky-600 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                        Semua Kelas Ajar
                    </a>
                    @foreach($kelasDosen as $cls)
                        <a href="{{ route('dosen.mahasiswa', ['kelas' => $cls]) }}" class="px-4 py-2 rounded-xl text-xs font-black transition-all border {{ $selectedKelas === $cls ? 'bg-sky-600 text-white border-sky-600 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                            Kelas {{ $cls }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mahasiswa Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" data-aos="fade-up">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-base font-extrabold text-slate-900">Daftar Mahasiswa {{ $selectedKelas !== 'semua' ? '— Kelas ' . $selectedKelas : '' }}</h3>
                <span class="text-xs font-bold text-slate-500">{{ $mahasiswas->count() }} Mahasiswa Ditemukan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Nama Mahasiswa</th>
                            <th class="py-3.5 px-6">NIM</th>
                            <th class="py-3.5 px-6">Kelas</th>
                            <th class="py-3.5 px-6">Jurusan</th>
                            <th class="py-3.5 px-6">Email Akun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($mahasiswas as $mhs)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 font-black text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($mhs->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm">{{ $mhs->name }}</span>
                                        <span class="text-[11px] text-slate-400">Angkatan {{ $mhs->angkatan ?? 29 }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-mono font-bold text-slate-800">
                                    {{ $mhs->nim ?? '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-sky-100 text-sky-800 font-black rounded-lg text-xs">
                                        Kelas {{ $mhs->kelas ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $mhs->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                        {{ $mhs->jurusan ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-mono text-slate-600">
                                    {{ $mhs->email }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-medium">Tidak ada mahasiswa yang ditemukan untuk kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
