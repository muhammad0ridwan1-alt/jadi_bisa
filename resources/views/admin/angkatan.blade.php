<x-app-layout>
    <x-slot name="header">
        Master Angkatan & Tahun Ajaran (Admin)
    </x-slot>

    <div x-data="{ addModal: false }" class="space-y-6">

    <!-- Page Header Banner -->
    <div data-aos="fade-down">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Master Angkatan</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    Kelola data angkatan kampus dan tahun ajaran aktif.
                </p>
            </div>
            <button @click="addModal = true" class="px-5 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Angkatan</span>
            </button>
        </div>
    </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Angkatan Terdaftar</h3>
                    <p class="text-xs text-slate-500">Saat angkatan baru dibuka, buat angkatan di sini lalu tambahkan Kelas & Matkul khusus angkatan tersebut.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-100/80 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-6">Angkatan</th>
                            <th class="py-3.5 px-6">Tahun Ajaran</th>
                            <th class="py-3.5 px-6 text-center">Jumlah Kelas</th>
                            <th class="py-3.5 px-6 text-center">Status</th>
                            <th class="py-3.5 px-6 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($angkatans as $ang)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <span class="text-base font-black text-slate-900">Angkatan {{ $ang->nomor_angkatan }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-slate-100 font-mono font-bold rounded-lg text-xs">{{ $ang->tahun_ajaran }}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 bg-sky-50 text-sky-700 font-bold rounded-full border border-sky-200">
                                        {{ \App\Models\KelasList::where('angkatan', $ang->nomor_angkatan)->count() }} Kelas
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($ang->is_active)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-extrabold text-xs rounded-full border border-emerald-200">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 font-bold text-xs rounded-full">Arsip</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.kelas_management', ['angkatan' => $ang->nomor_angkatan]) }}" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold rounded-xl text-xs border border-sky-200">
                                            + Kelola Kelas
                                        </a>
                                        <form action="{{ route('admin.angkatan.destroy', $ang->id) }}" method="POST" onsubmit="return confirm('Hapus Angkatan {{ $ang->nomor_angkatan }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl text-xs border border-rose-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-bold">Belum ada data angkatan. Silakan tambah di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL TAMBAH ANGKATAN -->
        <div x-show="addModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="addModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-200 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Tambah Angkatan Baru</h3>
                    <button @click="addModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form action="{{ route('admin.angkatan.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Angkatan:</label>
                        <input type="number" name="nomor_angkatan" placeholder="misal: 30" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tahun Ajaran:</label>
                        <input type="text" name="tahun_ajaran" placeholder="misal: 2026/2027" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="addModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black rounded-xl shadow-sm">Simpan Angkatan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    </div>
</x-app-layout>

