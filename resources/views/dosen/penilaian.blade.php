<x-app-layout>
    <x-slot name="header">
        Penilaian Kuis
    </x-slot>

    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-signal-blue-700 bg-white hover:bg-signal-blue-50 border border-gray-200 hover:border-signal-blue-200 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Penilaian Kuis: {{ $modul->title }}</h2>
            <p class="text-sm font-medium text-gray-500">{{ $modul->mataKuliah->name }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Daftar Pengumpulan</h3>
            <span class="px-3 py-1 bg-signal-blue-100 text-signal-blue-700 text-sm font-bold rounded-full">
                {{ $nilais->count() }} Mahasiswa
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Nilai (0-100)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($nilais as $nilai)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-signal-blue-100 text-signal-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($nilai->mahasiswa->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="block font-medium text-gray-900">{{ $nilai->mahasiswa->name }}</span>
                                        <span class="block text-xs text-gray-500">{{ $nilai->mahasiswa->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($nilai->status === 'graded')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Sudah Dinilai</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">Menunggu Penilaian</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('dosen.nilai.update', $nilai->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    <input type="number" name="score" min="0" max="100" value="{{ $nilai->score }}" required class="w-20 rounded-lg border-gray-300 focus:ring-signal-blue-600 focus:border-signal-blue-600 text-sm text-center font-bold">
                                    <button type="submit" class="bg-signal-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-signal-blue-700 transition-colors shadow-sm">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 px-6 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                Belum ada mahasiswa yang mengumpulkan kuis ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
