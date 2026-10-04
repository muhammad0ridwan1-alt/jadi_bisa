<x-app-layout>
    <x-slot name="header">
        Kelola Master Mata Kuliah & Dosen Pengampu (Admin)
    </x-slot>

    <div x-data="{ addModal: false, editModal: false, activeEdit: { id: '', name: '', kode_mk: '', jurusan: 'Administrasi Perkantoran', cawu: 1, angkatan: 29, tahun_ajaran: '2025/2026', dosen_id: '' } }" class="space-y-6">

    <!-- Page Header Banner -->
    <div data-aos="fade-down">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Master Mata Kuliah</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Kelola kurikulum mata kuliah, kode MK, cawu, dan Dosen Pengampu.
                </p>
            </div>
            <button @click="addModal = true" class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Mata Kuliah</span>
            </button>
        </div>
    </div>

        <!-- Top Angkatan Selector -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-black text-slate-600">Filter Angkatan:</span>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($allAngkatans as $ang)
                        <a href="{{ route('admin.matkul', ['angkatan' => $ang->nomor_angkatan]) }}" class="px-4 py-2 rounded-xl text-xs font-black transition-all border {{ (int)$selectedAngkatan === (int)$ang->nomor_angkatan ? 'bg-sky-600 text-white border-sky-600 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                            Angkatan {{ $ang->nomor_angkatan }} ({{ $ang->tahun_ajaran }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Kurikulum & Mata Kuliah</h3>
                    <p class="text-xs text-slate-500">Terdaftar {{ $mataKuliahs->count() }} Mata Kuliah aktif dalam database.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 text-center w-12">No</th>
                            <th class="py-3.5 px-4">Kode & Mata Kuliah</th>
                            <th class="py-3.5 px-4">Jurusan</th>
                            <th class="py-3.5 px-4 text-center">Cawu</th>
                            <th class="py-3.5 px-4 text-center">Angkatan & Tahun</th>
                            <th class="py-3.5 px-4">Dosen Pengampu</th>
                            <th class="py-3.5 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($mataKuliahs as $idx => $mk)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 bg-sky-50 text-sky-800 font-mono text-[11px] font-bold rounded border border-sky-200 mr-1.5">{{ $mk->kode_mk }}</span>
                                    <strong class="text-slate-900">{{ $mk->name }}</strong>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $mk->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-900' }}">
                                        {{ $mk->jurusan }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2 py-0.5 bg-slate-100 font-bold rounded text-xs">Cawu {{ $mk->cawu }}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-slate-800">Angkatan {{ $mk->angkatan ?? 29 }}</span>
                                    <span class="block text-[10px] text-slate-400 font-mono">{{ $mk->tahun_ajaran ?? '2025/2026' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($mk->dosen)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 font-black text-[10px] flex items-center justify-center">
                                                {{ substr($mk->dosen->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-900">{{ $mk->dosen->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Belum ditetapakan</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="activeEdit = {
                                            id: {{ $mk->id }},
                                            name: '{{ addslashes($mk->name) }}',
                                            kode_mk: '{{ addslashes($mk->kode_mk) }}',
                                            jurusan: '{{ addslashes($mk->jurusan) }}',
                                            cawu: {{ $mk->cawu }},
                                            angkatan: {{ $mk->angkatan ?? 29 }},
                                            tahun_ajaran: '{{ addslashes($mk->tahun_ajaran ?? '2025/2026') }}',
                                            dosen_id: '{{ $mk->dosen_id }}'
                                        }; editModal = true;" class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-200">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.matkul.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Hapus Mata Kuliah {{ addslashes($mk->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-lg border border-rose-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400 font-bold">Belum ada mata kuliah terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL 1: TAMBAH MATKUL -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Tambah Mata Kuliah Baru</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.matkul.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Mata Kuliah:</label>
                        <input type="text" name="name" placeholder="misal: Manajemen Perkantoran Modern" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kode MK:</label>
                            <input type="text" name="kode_mk" placeholder="misal: AP-101" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-mono font-medium focus:ring-2 focus:ring-sky-600">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jurusan:</label>
                            <select name="jurusan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                                <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Cawu:</label>
                            <select name="cawu" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                <option value="1">Cawu 1</option>
                                <option value="2">Cawu 2</option>
                                <option value="3">Cawu 3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Angkatan:</label>
                            <input type="number" name="angkatan" value="29" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran:</label>
                            <input type="text" name="tahun_ajaran" value="2025/2026" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dosen Pengampu:</label>
                        <select name="dosen_id" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            <option value="">-- Pilih Dosen Pengampu (Opsional) --</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Mata Kuliah</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 2: EDIT MATKUL -->
        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="editModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Edit Data Mata Kuliah</h3>
                    <button @click="editModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('admin/matkul') }}/' + activeEdit.id" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Mata Kuliah:</label>
                        <input type="text" name="name" x-model="activeEdit.name" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kode MK:</label>
                            <input type="text" name="kode_mk" x-model="activeEdit.kode_mk" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-mono font-medium focus:ring-2 focus:ring-sky-600">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jurusan:</label>
                            <select name="jurusan" x-model="activeEdit.jurusan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                                <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Cawu:</label>
                            <select name="cawu" x-model="activeEdit.cawu" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                                <option value="1">Cawu 1</option>
                                <option value="2">Cawu 2</option>
                                <option value="3">Cawu 3</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Angkatan:</label>
                            <input type="number" name="angkatan" x-model="activeEdit.angkatan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran:</label>
                            <input type="text" name="tahun_ajaran" x-model="activeEdit.tahun_ajaran" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dosen Pengampu:</label>
                        <select name="dosen_id" x-model="activeEdit.dosen_id" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-sky-600">
                            <option value="">-- Pilih Dosen Pengampu (Opsional) --</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="editModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold rounded-xl shadow-sm">Update Mata Kuliah</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
