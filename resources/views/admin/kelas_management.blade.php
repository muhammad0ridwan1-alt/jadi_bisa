<x-app-layout>
    <x-slot name="header">
        Kelola Master Kelas per Angkatan (Admin)
    </x-slot>

    <div x-data="{ addModal: false, editModal: false, activeEdit: { id: '', name: '', jurusan: 'Administrasi Perkantoran', angkatan: '{{ $selectedAngkatan }}' } }" class="space-y-6">

        <!-- Page Header Banner -->
        <div data-aos="fade-down">
            <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black text-white">Master Kelas</h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-normal">
                        Daftar kelas perkuliahan untuk Angkatan {{ $selectedAngkatan }}.
                    </p>
                </div>
                <button @click="addModal = true" class="px-5 py-3 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ Tambah Kelas</span>
                </button>
            </div>
        </div>

        <!-- Top Angkatan Selector -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-xs font-black text-slate-600">Pilih Angkatan:</span>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($allAngkatans as $ang)
                        <a href="{{ route('admin.kelas_management', ['angkatan' => $ang->nomor_angkatan]) }}" class="px-4 py-2 rounded-xl text-xs font-black transition-all border {{ (int)$selectedAngkatan === (int)$ang->nomor_angkatan ? 'bg-sky-600 text-white border-sky-600 shadow-sm' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                            Angkatan {{ $ang->nomor_angkatan }} ({{ $ang->tahun_ajaran }})
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Kelas — Angkatan {{ $selectedAngkatan }}</h3>
                    <p class="text-xs text-slate-500">Seluruh jadwal dan mahasiswa Angkatan {{ $selectedAngkatan }} akan memilih dari daftar kelas ini.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-sky-100 text-sky-800 rounded-full">
                    Total {{ $kelases->count() }} Kelas
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Nama Kelas</th>
                            <th class="py-3.5 px-6">Jurusan</th>
                            <th class="py-3.5 px-6 text-center">Angkatan</th>
                            <th class="py-3.5 px-6 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($kelases as $k)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="text-base font-black text-slate-900">Kelas {{ $k->name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $k->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-900' }}">
                                        {{ $k->jurusan }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-slate-800">
                                    Angkatan {{ $k->angkatan }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="editModal = true; activeEdit = { id: '{{ $k->id }}', name: '{{ $k->name }}', jurusan: '{{ $k->jurusan }}', angkatan: '{{ $k->angkatan }}' }" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold rounded-xl text-xs border border-sky-200 transition-all active:scale-95">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.kelas_management.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus Kelas {{ $k->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs border border-rose-200 transition-all active:scale-95">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 font-bold">Belum ada kelas yang dibuat untuk Angkatan {{ $selectedAngkatan }}. Klik "+ Tambah Kelas" di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL TAMBAH KELAS -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Tambah Kelas — Angkatan {{ $selectedAngkatan }}</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.kelas_management.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <input type="hidden" name="angkatan" value="{{ $selectedAngkatan }}">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Kelas:</label>
                        <input type="text" name="name" placeholder="misal: AP-1, BM-1, 3A, 3B" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jurusan:</label>
                        <select name="jurusan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                            <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                            <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT KELAS -->
        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="editModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Edit Data Kelas</h3>
                    <button @click="editModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form :action="'{{ url('admin/kelas-management') }}/' + activeEdit.id" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Angkatan:</label>
                        <select name="angkatan" x-model="activeEdit.angkatan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                            @foreach($allAngkatans as $ang)
                                <option value="{{ $ang->nomor_angkatan }}">Angkatan {{ $ang->nomor_angkatan }} ({{ $ang->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Kelas:</label>
                        <input type="text" name="name" x-model="activeEdit.name" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jurusan:</label>
                        <select name="jurusan" x-model="activeEdit.jurusan" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                            <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                            <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                        </select>
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="editModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm">Perbarui Kelas</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
