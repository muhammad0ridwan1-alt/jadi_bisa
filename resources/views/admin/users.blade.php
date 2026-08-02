<x-app-layout>
    <x-slot name="header">
        Manajemen {{ ucfirst($role) }}
    </x-slot>

    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-slate-900">Daftar {{ ucfirst($role) }}</h2>
            <p class="text-slate-500 text-xs sm:text-sm">Kelola data {{ $role }} yang terdaftar di dalam sistem.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs sm:text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm active:scale-95 self-start sm:self-auto">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-3 mb-16">
        @forelse($users as $user)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 space-y-2">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-xs">{{ $user->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 p-1">Hapus</button>
                    </form>
                </div>
                @if($role === 'mahasiswa')
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-md {{ $user->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-50 text-sky-700' : 'bg-emerald-50 text-emerald-700' }}">
                        {{ $user->jurusan ?: 'Belum diatur' }}
                    </span>
                @endif
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-xl border border-dashed border-slate-300">
                <p class="text-slate-500 text-xs">Tidak ada data {{ $role }} ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        @if($role === 'mahasiswa')
                            <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jurusan</th>
                        @endif
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-sm">{{ $user->email }}</td>
                            @if($role === 'mahasiswa')
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-md {{ $user->jurusan === 'Administrasi Perkantoran' ? 'bg-sky-50 text-sky-700' : 'bg-emerald-50 text-emerald-700' }}">
                                        {{ $user->jurusan ?: 'Belum diatur' }}
                                    </span>
                                </td>
                            @endif
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $role === 'mahasiswa' ? '4' : '3' }}" class="py-12 px-6 text-center text-slate-500 text-sm">
                                Tidak ada data {{ $role }} ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
