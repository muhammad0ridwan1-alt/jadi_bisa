<x-app-layout>
    <x-slot name="header">
        Kelola Jadwal Perkuliahan & Cawu (Admin)
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Jadwal Perkuliahan</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Pengaturan jadwal sesi kuliah per kelas, dosen pengampu, dan ruangan.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-slate-800 px-5 py-3 rounded-2xl border border-slate-700 text-center shadow-xs">
                    <span class="block text-[10px] text-sky-400 font-extrabold uppercase tracking-wider">Angkatan</span>
                    <span class="block text-2xl font-black text-white">Angkatan {{ $selectedAngkatan ?? 29 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ addModal: false, editModal: false, activeEdit: { id: '', mata_kuliah_id: '', dosen_id: '', kelas: '{{ $selectedKelas }}', hari: 'Senin', jam_mulai: '08:00', jam_selesai: '09:30', ruangan: '' }, dayFilter: 'semua' }" class="space-y-6">

        <!-- Top Navigation Bar & Action Buttons -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-4">
                <!-- Filter Angkatan & Class Pills -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <span class="text-xs font-black text-slate-600 pl-2">Angkatan:</span>
                        <select onchange="window.location.href='{{ route('admin.jadwal') }}?kelas={{ $selectedKelas }}&angkatan=' + this.value" class="py-1 px-3 bg-white border border-slate-200 rounded-lg text-xs font-black text-sky-800">
                            @foreach($allAngkatans as $ang)
                                <option value="{{ $ang->nomor_angkatan }}" {{ (isset($selectedAngkatan) && $selectedAngkatan == $ang->nomor_angkatan) ? 'selected' : '' }}>Angkatan {{ $ang->nomor_angkatan }} ({{ $ang->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-xs font-bold text-slate-500 mr-1">Pilih Kelas:</span>
                        @foreach($allClasses as $c)
                            <a href="{{ route('admin.jadwal', ['kelas' => $c, 'angkatan' => $selectedAngkatan ?? 29]) }}" 
                                class="px-3 py-1.5 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 border {{ $selectedKelas === $c ? 'bg-sky-600 text-white border-sky-600 shadow-md' : 'bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-sky-700 border-slate-200' }}">
                                <span>Kelas {{ $c }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Add Schedule Button -->
                <button @click="addModal = true" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-sm active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Sesi Jadwal</span>
                </button>
            </div>

            <!-- Sub Filter: Filter Hari -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="font-bold text-slate-400 mr-1">Filter Hari:</span>
                <button @click="dayFilter = 'semua'" :class="dayFilter === 'semua' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1 rounded-lg font-bold transition-all">Semua Hari</button>
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hFilter)
                    <button @click="dayFilter = '{{ $hFilter }}'" :class="dayFilter === '{{ $hFilter }}' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1 rounded-lg font-bold transition-all">{{ $hFilter }}</button>
                @endforeach
            </div>
        </div>

        <!-- Schedule List Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Sesi Perkuliahan — Kelas {{ $selectedKelas }}</h3>
                    <p class="text-xs text-slate-500">Terurut berdasarkan Hari dan Jam Pelaksanaan.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-sky-100 text-sky-800 rounded-full">
                    Total: {{ $jadwals->count() }} Sesi
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 text-center w-28">Hari</th>
                            <th class="py-3.5 px-4 text-center w-36">Waktu (Jam)</th>
                            <th class="py-3.5 px-4">Mata Kuliah</th>
                            <th class="py-3.5 px-4">Dosen Pengampu</th>
                            <th class="py-3.5 px-4 text-center">Ruangan</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($jadwals as $j)
                            <tr x-show="dayFilter === 'semua' || dayFilter === '{{ $j->hari }}'" class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg text-xs font-extrabold {{ $j->hari === 'Senin' ? 'bg-sky-100 text-sky-800' : ($j->hari === 'Selasa' ? 'bg-indigo-100 text-indigo-800' : ($j->hari === 'Rabu' ? 'bg-slate-100 text-slate-800' : ($j->hari === 'Kamis' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'))) }}">
                                        {{ $j->hari }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center font-mono font-bold text-slate-700">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }} WIB
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 bg-sky-100 text-sky-800 font-mono text-xs font-black rounded">
                                            {{ $j->mataKuliah->kode_mk }}
                                        </span>
                                        <span class="font-bold text-slate-900">{{ $j->mataKuliah->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-bold text-slate-800">{{ $j->dosen->name ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-extrabold border border-slate-200">
                                        {{ $j->ruangan }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="activeEdit = {
                                            id: {{ $j->id }},
                                            mata_kuliah_id: '{{ $j->mata_kuliah_id }}',
                                            dosen_id: '{{ $j->dosen_id }}',
                                            kelas: '{{ $j->kelas }}',
                                            hari: '{{ $j->hari }}',
                                            jam_mulai: '{{ substr($j->jam_mulai, 0, 5) }}',
                                            jam_selesai: '{{ substr($j->jam_selesai, 0, 5) }}',
                                            ruangan: '{{ addslashes($j->ruangan) }}'
                                        }; editModal = true;" 
                                        class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-lg text-xs border border-amber-200 transition-colors">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus sesi jadwal perkuliahan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg text-xs border border-rose-200 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    Belum ada sesi jadwal untuk Kelas {{ $selectedKelas }}. Klik "+ Tambah Sesi Jadwal" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL 1: TAMBAH JADWAL BARU -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Tambah Sesi Jadwal (Kelas {{ $selectedKelas }})</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <input type="hidden" name="kelas" value="{{ $selectedKelas }}">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mata Kuliah:</label>
                        <select name="mata_kuliah_id" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}">[{{ $mk->kode_mk }}] {{ $mk->name }} ({{ $mk->jurusan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dosen Pengampu:</label>
                        <select name="dosen_id" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Hari:</label>
                            <select name="hari" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ruangan:</label>
                            <input type="text" name="ruangan" placeholder="misal: R.A atau R.I1" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jam Mulai:</label>
                            <input type="time" name="jam_mulai" value="08:00" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jam Selesai:</label>
                            <input type="time" name="jam_selesai" value="09:30" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-3">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT JADWAL -->
        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="editModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Edit Sesi Jadwal Perkuliahan</h3>
                    <button @click="editModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('admin/jadwal') }}/' + activeEdit.id" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="kelas" :value="activeEdit.kelas">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Mata Kuliah:</label>
                        <select name="mata_kuliah_id" x-model="activeEdit.mata_kuliah_id" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}">[{{ $mk->kode_mk }}] {{ $mk->name }} ({{ $mk->jurusan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dosen Pengampu:</label>
                        <select name="dosen_id" x-model="activeEdit.dosen_id" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Hari:</label>
                            <select name="hari" x-model="activeEdit.hari" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ruangan:</label>
                            <input type="text" name="ruangan" x-model="activeEdit.ruangan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jam Mulai:</label>
                            <input type="time" name="jam_mulai" x-model="activeEdit.jam_mulai" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jam Selesai:</label>
                            <input type="time" name="jam_selesai" x-model="activeEdit.jam_selesai" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-3">
                        <button type="button" @click="editModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl shadow-sm">Update Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
