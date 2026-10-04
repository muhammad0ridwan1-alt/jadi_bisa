<x-app-layout>
    <x-slot name="header">
        Manajemen {{ ucfirst($role) }} {{ $role === 'dosen' ? '& Mata Kuliah' : '& Cawu' }}
    </x-slot>

    <!-- Page Header Banner -->
    <div data-aos="fade-down" class="mb-6">
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl shadow-md flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-800">
            <div class="space-y-1.5 max-w-2xl">
                <h1 class="text-2xl sm:text-3xl font-black text-white">Kelola Data {{ ucfirst($role) }}</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">
                    @if($role === 'dosen')
                        Pengaturan akun dosen, kode dosen, mata kuliah yang diampu, dan jumlah sesi tiap kelas.
                    @else
                        Pengaturan akun mahasiswa, NIM, jurusan, kelas, dan cawu akademik.
                    @endif
                </p>
            </div>
            
            <!-- Quick Role Switcher Buttons -->
            <div class="flex items-center gap-2 bg-slate-800/90 p-1.5 rounded-2xl border border-slate-700/80 shrink-0">
                <a href="{{ route('admin.users', 'mahasiswa') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 {{ $role === 'mahasiswa' ? 'bg-sky-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    <span>Mahasiswa</span>
                </a>
                <a href="{{ route('admin.users', 'dosen') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 {{ $role === 'dosen' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    <span>Dosen</span>
                </a>
            </div>
        </div>
    </div>

    <div x-data="{ 
        editModal: false, 
        searchQuery: '',
        filterKelas: 'semua',
        activeUser: { 
            id: '', 
            name: '', 
            email: '', 
            role: '{{ $role }}',
            jurusan: 'Administrasi Perkantoran', 
            kelas: '1A', 
            cawu: 1, 
            angkatan: 30, 
            tahun_ajaran: '2026/2027', 
            nim: '',
            kode_dosen: '',
            mata_kuliah_diampu: '',
            sesi_per_kelas: 1,
            phone: ''
        },
        openEdit(user) {
            this.activeUser = Object.assign({}, user);
            this.editModal = true;
        }
    }" class="space-y-6">

        <!-- Toolbar & Filter Controls -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Bar Input -->
                <div class="relative flex-1 max-w-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" x-model="searchQuery" 
                           placeholder="{{ $role === 'dosen' ? 'Cari nama, email, kode, atau matkul...' : 'Cari nama, email, atau NIM mahasiswa...' }}" 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:ring-sky-600 focus:border-sky-600 font-medium">
                </div>

                @if($role === 'mahasiswa')
                    <!-- Angkatan Selector -->
                    <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <span class="text-xs font-black text-slate-600 pl-2">Angkatan:</span>
                        <select @change="window.location.href='{{ url('admin/users/' . $role) }}?angkatan=' + $event.target.value" class="py-1 px-3 bg-white border border-slate-200 rounded-lg text-xs font-black text-sky-800">
                            @foreach($allAngkatans as $ang)
                                <option value="{{ $ang->nomor_angkatan }}" {{ (isset($selectedAngkatan) && $selectedAngkatan == $ang->nomor_angkatan) ? 'selected' : '' }}>Angkatan {{ $ang->nomor_angkatan }} ({{ $ang->tahun_ajaran }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            @if($role === 'mahasiswa')
                <!-- Filter Kelas Pills (Dynamic from database) -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-xs font-bold text-slate-400 mr-1">Kelas:</span>
                    <button @click="filterKelas = 'semua'" :class="filterKelas === 'semua' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1 rounded-lg text-xs font-bold transition-all">Semua</button>
                    @foreach($availableClasses as $clsPill)
                        <button @click="filterKelas = '{{ $clsPill }}'" :class="filterKelas === '{{ $clsPill }}' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all">{{ $clsPill }}</button>
                    @endforeach
                </div>
            @else
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                    <span>Total: {{ count($users) }} Dosen Pengampu</span>
                </div>
            @endif
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-slate-600 uppercase font-bold text-[11px] tracking-wider">
                            <th class="py-3.5 px-6">Nama Pengguna</th>
                            @if($role === 'dosen')
                                <th class="py-3.5 px-6 text-center">Kode Dosen</th>
                                <th class="py-3.5 px-6">Email Login</th>
                                <th class="py-3.5 px-6">Mata Kuliah Diampu</th>
                                <th class="py-3.5 px-6 text-center">Sesi / Kelas</th>
                            @else
                                <th class="py-3.5 px-6">Email / Username</th>
                                <th class="py-3.5 px-6 text-center">NIM</th>
                                <th class="py-3.5 px-6">Jurusan & Kelas</th>
                                <th class="py-3.5 px-6 text-center whitespace-nowrap">Cawu</th>
                            @endif
                            <th class="py-3.5 px-6 text-right w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($users as $user)
                            <tr x-show="
                                (searchQuery === '' 
                                    || '{{ strtolower(addslashes($user->name)) }}'.includes(searchQuery.toLowerCase()) 
                                    || '{{ strtolower(addslashes($user->email)) }}'.includes(searchQuery.toLowerCase()) 
                                    || '{{ strtolower(addslashes($user->nim ?? '')) }}'.includes(searchQuery.toLowerCase())
                                    || '{{ strtolower(addslashes($user->kode_dosen ?? '')) }}'.includes(searchQuery.toLowerCase())
                                    || '{{ strtolower(addslashes($user->mata_kuliah_diampu ?? '')) }}'.includes(searchQuery.toLowerCase())
                                )
                                && (filterKelas === 'semua' || '{{ $user->kelas }}' === filterKelas)
                            " class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- User Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl {{ $role === 'dosen' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-sky-100 text-sky-800 border-sky-200' }} font-black flex items-center justify-center text-xs shrink-0 shadow-2xs border">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-extrabold text-slate-900 leading-snug">{{ $user->name }}</span>
                                            <span class="inline-block px-2 py-0.2 bg-slate-100 text-slate-500 rounded text-[10px] font-bold capitalize">Role: {{ $user->role }}</span>
                                        </div>
                                    </div>
                                </td>

                                @if($role === 'dosen')
                                    <!-- Kode Dosen -->
                                    <td class="py-4 px-6 text-center">
                                        @if($user->kode_dosen)
                                            <span class="font-mono font-black text-emerald-800 px-2.5 py-1 bg-emerald-50 rounded-lg text-xs border border-emerald-200 shadow-2xs">
                                                {{ $user->kode_dosen }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-mono text-xs">-</span>
                                        @endif
                                    </td>

                                    <!-- Email -->
                                    <td class="py-4 px-6">
                                        <span class="font-mono text-slate-700 font-bold text-xs bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200/60">
                                            {{ $user->email }}
                                        </span>
                                    </td>

                                    <!-- Mata Kuliah Diampu -->
                                    <td class="py-4 px-6">
                                        @if($user->mata_kuliah_diampu)
                                            <span class="inline-flex items-center gap-1.5 font-extrabold text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg border border-indigo-200/60">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                {{ $user->mata_kuliah_diampu }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Belum diatur</span>
                                        @endif
                                    </td>

                                    <!-- Sesi / Kelas -->
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-900 rounded-lg text-xs font-black border border-amber-200 shadow-2xs whitespace-nowrap">
                                            {{ $user->sesi_per_kelas ?: 1 }} Sesi / Kelas
                                        </span>
                                    </td>
                                @else
                                    <!-- Email Mahasiswa -->
                                    <td class="py-4 px-6">
                                        <span class="font-mono text-slate-700 font-bold text-xs bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200/60">
                                            {{ $user->email }}
                                        </span>
                                    </td>

                                    <!-- NIM -->
                                    <td class="py-4 px-6 text-center">
                                        @if($user->nim)
                                            <span class="font-mono font-black text-slate-800 px-2.5 py-1 bg-slate-100 rounded-lg text-xs border border-slate-200">
                                                {{ $user->nim }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-mono text-xs">-</span>
                                        @endif
                                    </td>

                                    <!-- Jurusan & Kelas -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold border shadow-2xs {{ str_contains($user->jurusan ?? '', 'Bisnis') ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-sky-50 text-sky-800 border-sky-200' }}">
                                                {{ $user->jurusan ?: 'Administrasi Perkantoran' }}
                                            </span>
                                            <span class="px-2 py-0.5 bg-slate-900 text-white rounded-md text-[11px] font-black shrink-0">
                                                {{ $user->kelas ?: '1A' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Cawu Badge -->
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-900 rounded-xl text-xs font-black border border-amber-200 shadow-2xs whitespace-nowrap">
                                            <span>Cawu {{ $user->cawu ?: 1 }}</span>
                                        </span>
                                    </td>
                                @endif

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Masuk Sebagai / Impersonate Button -->
                                        <form action="{{ route('admin.impersonate', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Masuk & cek sistem sebagai {{ $user->name }}" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-extrabold rounded-xl text-xs border border-sky-200 transition-all active:scale-95 shadow-2xs flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <span>Masuk Sebagai</span>
                                            </button>
                                        </form>

                                        <button @click="openEdit({
                                            id: {{ $user->id }},
                                            name: '{{ addslashes($user->name) }}',
                                            email: '{{ addslashes($user->email) }}',
                                            role: '{{ $user->role }}',
                                            jurusan: '{{ addslashes($user->jurusan ?? 'Administrasi Perkantoran') }}',
                                            kelas: '{{ addslashes($user->kelas ?? '1A') }}',
                                            cawu: {{ $user->cawu ?? 1 }},
                                            angkatan: {{ $user->angkatan ?? 30 }},
                                            tahun_ajaran: '{{ addslashes($user->tahun_ajaran ?? '2026/2027') }}',
                                            nim: '{{ addslashes($user->nim ?? '') }}',
                                            kode_dosen: '{{ addslashes($user->kode_dosen ?? '') }}',
                                            mata_kuliah_diampu: '{{ addslashes($user->mata_kuliah_diampu ?? '') }}',
                                            sesi_per_kelas: {{ $user->sesi_per_kelas ?? 1 }},
                                            phone: '{{ addslashes($user->phone ?? '') }}'
                                        })" 
                                        class="px-3.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold rounded-xl text-xs border border-amber-200 transition-all active:scale-95 shadow-2xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span>Edit Data</span>
                                        </button>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ addslashes($user->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold rounded-xl text-xs border border-rose-200 transition-all active:scale-95 shadow-2xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $role === 'mahasiswa' ? '6' : '6' }}" class="py-12 px-6 text-center text-slate-400 font-medium">
                                    Tidak ada data {{ $role }} ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View (shown on small screens) -->
        <div class="sm:hidden space-y-3 mb-16">
            @forelse($users as $user)
                <div x-show="
                    (searchQuery === '' 
                        || '{{ strtolower(addslashes($user->name)) }}'.includes(searchQuery.toLowerCase()) 
                        || '{{ strtolower(addslashes($user->email)) }}'.includes(searchQuery.toLowerCase()) 
                        || '{{ strtolower(addslashes($user->nim ?? '')) }}'.includes(searchQuery.toLowerCase())
                        || '{{ strtolower(addslashes($user->kode_dosen ?? '')) }}'.includes(searchQuery.toLowerCase())
                        || '{{ strtolower(addslashes($user->mata_kuliah_diampu ?? '')) }}'.includes(searchQuery.toLowerCase())
                    )
                    && (filterKelas === 'semua' || '{{ $user->kelas }}' === filterKelas)
                " class="bg-white rounded-2xl p-4 shadow-2xs border border-slate-200/80 space-y-3">
                    
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl {{ $role === 'dosen' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-sky-100 text-sky-800 border-sky-200' }} font-black flex items-center justify-center text-xs shrink-0 border">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $user->name }}</h3>
                                <p class="text-[11px] font-mono text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        @if($role === 'dosen')
                            @if($user->kode_dosen)
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 rounded-lg text-xs font-mono font-black shrink-0 whitespace-nowrap">
                                    {{ $user->kode_dosen }}
                                </span>
                            @endif
                        @else
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg text-xs font-black shrink-0 whitespace-nowrap">
                                Cawu {{ $user->cawu ?: 1 }}
                            </span>
                        @endif
                    </div>

                    @if($role === 'dosen')
                        <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="text-slate-400 font-bold">Matkul:</span>
                                <span class="font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200/60">{{ $user->mata_kuliah_diampu ?: 'Belum diatur' }}</span>
                            </div>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-800 font-black rounded text-[10px] border border-amber-200">{{ $user->sesi_per_kelas ?: 1 }} Sesi / Kelas</span>
                        </div>
                    @else
                        <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                            <span class="font-mono text-slate-500 font-bold">NIM: {{ $user->nim ?: '-' }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 bg-sky-50 text-sky-700 font-bold rounded text-[10px] border border-sky-200">{{ $user->jurusan }}</span>
                                <span class="px-2 py-0.5 bg-slate-900 text-white font-black rounded text-[10px]">{{ $user->kelas }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-end gap-2">
                        <!-- Masuk Sebagai / Impersonate Button -->
                        <form action="{{ route('admin.impersonate', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Masuk & cek sistem sebagai {{ $user->name }}" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 font-extrabold rounded-xl text-xs border border-sky-200 active:scale-95 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <span>Masuk Sebagai</span>
                            </button>
                        </form>

                        <button @click="openEdit({
                            id: {{ $user->id }},
                            name: '{{ addslashes($user->name) }}',
                            email: '{{ addslashes($user->email) }}',
                            role: '{{ $user->role }}',
                            jurusan: '{{ addslashes($user->jurusan ?? 'Administrasi Perkantoran') }}',
                            kelas: '{{ addslashes($user->kelas ?? '1A') }}',
                            cawu: {{ $user->cawu ?? 1 }},
                            angkatan: {{ $user->angkatan ?? 30 }},
                            tahun_ajaran: '{{ addslashes($user->tahun_ajaran ?? '2026/2027') }}',
                            nim: '{{ addslashes($user->nim ?? '') }}',
                            kode_dosen: '{{ addslashes($user->kode_dosen ?? '') }}',
                            mata_kuliah_diampu: '{{ addslashes($user->mata_kuliah_diampu ?? '') }}',
                            sesi_per_kelas: {{ $user->sesi_per_kelas ?? 1 }},
                            phone: '{{ addslashes($user->phone ?? '') }}'
                        })" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold rounded-xl text-xs border border-amber-200 active:scale-95">
                            Edit Data
                        </button>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus {{ addslashes($user->name) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold rounded-xl text-xs border border-rose-200 active:scale-95">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                    <p class="text-slate-400 font-bold text-xs">Tidak ada data {{ $role }}.</p>
                </div>
            @endforelse
        </div>

        <!-- EDIT USER MODAL -->
        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @click.self="editModal = false">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-6 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900">
                        Edit Data {{ ucfirst($role) }} {{ $role === 'dosen' ? '& Mata Kuliah' : '& Cawu' }}
                    </h3>
                    <button @click="editModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                @if(isset($errors) && $errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <div>• {{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <form :action="'{{ url('admin/users') }}/' + activeUser.id" method="POST" class="space-y-4 text-xs sm:text-sm">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-extrabold text-slate-700 mb-1">Nama Lengkap {{ $role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}:</label>
                        <input type="text" name="name" x-model="activeUser.name" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block font-extrabold text-slate-700 mb-1">Email / Username Login:</label>
                        <input type="email" name="email" x-model="activeUser.email" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-mono font-bold focus:ring-2 focus:ring-sky-600">
                    </div>

                    @if($role === 'dosen')
                        <!-- Dosen Specific Fields: Kode Dosen, Mata Kuliah Diampu, Sesi Per Kelas -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Kode Dosen:</label>
                                <input type="text" name="kode_dosen" x-model="activeUser.kode_dosen" placeholder="Contoh: AR, CA, EH" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-mono font-bold uppercase focus:ring-2 focus:ring-sky-600">
                                <p class="text-[10px] text-slate-400 mt-0.5">Kode inisial dosen (2-4 huruf)</p>
                            </div>
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Sesi / Kelas Tiap Minggu:</label>
                                <input type="number" name="sesi_per_kelas" x-model="activeUser.sesi_per_kelas" min="1" max="10" placeholder="1 atau 2" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                                <p class="text-[10px] text-slate-400 mt-0.5">Jumlah pertemuan per kelas</p>
                            </div>
                        </div>

                        <div>
                            <label class="block font-extrabold text-slate-700 mb-1">Mata Kuliah yang Diampu:</label>
                            <input type="text" name="mata_kuliah_diampu" x-model="activeUser.mata_kuliah_diampu" placeholder="Contoh: Blind System Typing III, Akuntansi II" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                            <p class="text-[11px] text-slate-500 mt-1">Nama mata kuliah utama yang diajar oleh dosen ini untuk kelas-kelasnya.</p>
                        </div>

                        <div>
                            <label class="block font-extrabold text-slate-700 mb-1">Jurusan / Bidang Keahlian:</label>
                            <select name="jurusan" x-model="activeUser.jurusan" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                                <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                                <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                                <option value="Umum">Umum / Kedua Jurusan</option>
                            </select>
                        </div>
                    @else
                        <!-- Mahasiswa Specific Fields -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">NIM Mahasiswa:</label>
                                <input type="text" name="nim" x-model="activeUser.nim" placeholder="misal: 30.1.001" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-mono font-bold focus:ring-2 focus:ring-sky-600">
                            </div>
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Cawu Berjalan:</label>
                                <select name="cawu" x-model="activeUser.cawu" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                                    <option value="1">Cawu 1</option>
                                    <option value="2">Cawu 2</option>
                                    <option value="3">Cawu 3</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Angkatan:</label>
                                <input type="number" name="angkatan" x-model="activeUser.angkatan" placeholder="misal: 30" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                            </div>
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Tahun Ajaran:</label>
                                <input type="text" name="tahun_ajaran" x-model="activeUser.tahun_ajaran" placeholder="misal: 2026/2027" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Jurusan:</label>
                                <select name="jurusan" x-model="activeUser.jurusan" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold bg-white focus:ring-2 focus:ring-sky-600">
                                    <option value="Administrasi Perkantoran">Administrasi Perkantoran</option>
                                    <option value="Bisnis Manajemen">Bisnis Manajemen</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-extrabold text-slate-700 mb-1">Nama Kelas:</label>
                                <input type="text" name="kelas" x-model="activeUser.kelas" placeholder="misal: 1A, AP-1, BM-1" required class="w-full py-2.5 px-3 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-sky-600">
                            </div>
                        </div>
                    @endif

                    <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="editModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl shadow-sm active:scale-95">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
