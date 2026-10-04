<x-app-layout>
    <x-slot name="header">
        WEEKLY LESSON TIMETABLE (Batch {{ auth()->user()->angkatan ?? 30 }} - Cawu {{ auth()->user()->cawu ?? 1 }})
    </x-slot>

    <!-- Custom CSS for PDF Timetable Print & High-End Visuals -->
    <style>
        @page {
            size: landscape;
            margin: 6mm;
        }
        @media print {
            aside, header, nav, .no-print, [data-aos] {
                display: none !important;
            }
            body {
                background: white !important;
                color: #0f172a !important;
                padding: 0 !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-container {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .overflow-x-auto {
                overflow: visible !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
            }
            th, td {
                border: 1px solid #94a3b8 !important;
                padding: 2px 1px !important;
                text-align: center !important;
                word-break: break-word !important;
            }
            .timetable-grid {
                font-size: 7.5pt !important;
            }
            .cell-hover {
                box-shadow: none !important;
                transform: none !important;
                background-color: #f0f9ff !important;
                border: 1px solid #bae6fd !important;
                padding: 2px !important;
            }
            .hide-scrollbar {
                max-height: none !important;
                overflow: visible !important;
            }
            tr {
                page-break-inside: avoid !important;
            }
        }
        .cell-hover {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .cell-hover:hover {
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 8px 20px -4px rgba(14, 165, 233, 0.3);
        }
    </style>

    <!-- Main Container -->
    <div x-data="{ selectedCell: null }" class="space-y-6 print-container mb-20 md:mb-6">

        <!-- DEDICATED PRINTABLE PDF DOCUMENT HEADER (Visible ONLY when printing to PDF) -->
        <div class="hidden print:block mb-4 pb-3 border-b-2 border-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase">BOGOR EDUCARE — WEEKLY LESSON TIMETABLE SHEET</h1>
                    <p class="text-xs font-bold text-slate-700">Official Campus Schedule | Batch {{ auth()->user()->angkatan ?? 30 }} (Cawu {{ auth()->user()->cawu ?? 1 }}) — TA {{ auth()->user()->tahun_ajaran ?? '2026/2027' }}</p>
                </div>
                <div class="text-right text-xs font-mono">
                    <p class="font-black text-sky-900 uppercase">STATUS: OFFICIAL TIMETABLE SHEET</p>
                    <p class="text-slate-500 font-bold">Tanggal Cetak: {{ date('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- HEADER BANNER -->
        <div data-aos="fade-down" class="bg-sky-600 text-white p-6 sm:p-8 rounded-2xl shadow-lg border border-sky-500 relative overflow-hidden no-print" style="background-color: #0284c7; color: #ffffff;">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Document Title Block -->
                <div class="space-y-1.5 max-w-2xl">
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                        Jadwal Perkuliahan Mingguan
                    </h1>
                    <p class="text-xs sm:text-sm text-sky-100 font-normal">
                        Matriks jadwal perkuliahan Angkatan {{ auth()->user()->angkatan ?? 30 }} Cawu {{ auth()->user()->cawu ?? 1 }} Bogor EduCARE.
                    </p>
                </div>

                <!-- Action Buttons: Print PDF & Fast Nav -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                    @if(auth()->user()->kelas)
                        <a href="{{ route('mahasiswa.jadwal.kelas', auth()->user()->kelas) }}" class="px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white font-black rounded-xl transition-all flex items-center justify-center gap-2 text-xs active:scale-95 shadow-md border border-slate-700">
                            <span>Jadwal Kelas Saya ({{ auth()->user()->kelas }}) →</span>
                        </a>
                    @endif
                    <a href="{{ route('mahasiswa.jadwal.print') }}" target="_blank" class="px-5 py-3 bg-white hover:bg-sky-50 text-sky-800 font-extrabold rounded-xl transition-all flex items-center justify-center gap-2 text-xs active:scale-95 shadow-md">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Cetak / Unduh PDF Timetable</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- DEDICATED CLASS SELECTION BAR (Right-Aligned Class Pill Navigation Bar) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col xl:flex-row items-center justify-between gap-4 no-print" data-aos="fade-up">
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="w-3 h-3 rounded-full bg-sky-600 animate-pulse"></div>
                <div>
                    <span class="text-xs font-black text-slate-900 uppercase tracking-wider block">HALAMAN DEDICATED PER KELAS:</span>
                    <span class="text-[11px] text-slate-500 font-medium block">Pilih kelas di sebelah kanan untuk melihat jadwal lengkap per hari:</span>
                </div>
            </div>

            <!-- Grid Columns (Aligned Right on XL screens) -->
            <div class="flex items-center gap-2 w-full xl:w-auto xl:justify-end">
                @php
                    $displayClasses = $classes ?? [];
                @endphp
                @if(count($displayClasses) > 0)
                    <div class="grid grid-cols-4 sm:grid-cols-8 gap-2 w-full xl:w-auto">
                        @foreach($displayClasses as $c)
                            <a href="{{ route('mahasiswa.jadwal.kelas', $c) }}" 
                               class="px-3 py-2.5 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5 border active:scale-95 hover:scale-105 {{ auth()->user()->kelas === $c ? 'bg-sky-600 text-white border-sky-600 shadow-md' : 'bg-slate-50 hover:bg-sky-50 text-slate-800 hover:text-sky-700 border-slate-200 hover:border-sky-300' }}">
                                <span>Kelas {{ $c }}</span>
                                @if(in_array($c, ['1G', '1H', '3G', '3H']) || str_contains($c, 'BM') || str_contains($c, 'G') || str_contains($c, 'H'))
                                    <span class="text-[9px] px-1 py-0.2 rounded bg-amber-500 text-slate-900 font-black shrink-0">BM</span>
                                @else
                                    <span class="text-[9px] px-1 py-0.2 rounded bg-sky-800 text-white font-black shrink-0">AP</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                        Belum ada pembagian kelas untuk Angkatan {{ $userAngkatan ?? 30 }}
                    </span>
                @endif
            </div>
        </div>

        @if(count($displayClasses) > 0)
        <!-- 📄 EXACT PDF REPLICA SHEET TABLE BOARD -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md overflow-hidden" data-aos="fade-up">
            <!-- Sheet Header Banner -->
            <div class="bg-slate-900 text-white p-4 sm:p-5 flex flex-wrap items-center justify-between border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-600 text-white font-black flex items-center justify-center text-sm shadow-md">
                        BEC
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-black tracking-wide text-white uppercase">WEEKLY LESSON TIMETABLE (Batch {{ auth()->user()->angkatan ?? 30 }} - Cawu {{ auth()->user()->cawu ?? 1 }})</h2>
                        <p class="text-[11px] text-slate-400 font-mono">TA {{ auth()->user()->tahun_ajaran ?? '2026/2027' }} — Bogor EduCARE Campus Sheet</p>
                    </div>
                </div>
                <div class="text-right text-[11px] font-mono text-slate-300">
                    <span class="block font-bold text-sky-400">STATUS: OFFICIAL TIMETABLE</span>
                    <span>AP (Kelas A-F) | BM (Kelas G-H)</span>
                </div>
            </div>

            <!-- PDF TIMETABLE MATRIX TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-[10px] font-sans timetable-grid">
                    <thead>
                        <!-- DAYS HEADER ROW -->
                        <tr class="bg-slate-800 text-white font-extrabold text-xs border-b border-slate-700 tracking-wider">
                            <th rowspan="2" class="py-3 px-2 border-r border-slate-700 w-16 bg-slate-900 align-middle text-slate-200">
                                Class / Time
                            </th>

                            <!-- Monday -->
                            <th colspan="4" class="py-2 px-2 border-r border-slate-700 bg-sky-950 text-sky-200 uppercase font-black">
                                Monday (Senin)
                            </th>

                            <!-- Tuesday -->
                            <th colspan="4" class="py-2 px-2 border-r border-slate-700 bg-indigo-950 text-indigo-200 uppercase font-black">
                                Tuesday (Selasa)
                            </th>

                            <!-- Wednesday -->
                            <th colspan="4" class="py-2 px-2 border-r border-slate-700 bg-slate-900 text-slate-200 uppercase font-black">
                                Wednesday (Rabu)
                            </th>

                            <!-- Thursday -->
                            <th colspan="4" class="py-2 px-2 border-r border-slate-700 bg-sky-950 text-sky-200 uppercase font-black">
                                Thursday (Kamis)
                            </th>

                            <!-- Friday -->
                            <th colspan="4" class="py-2 px-2 border-slate-700 bg-emerald-950 text-emerald-200 uppercase font-black">
                                Friday (Jumat)
                            </th>
                        </tr>

                        <!-- SLOTS TIME HEADER ROW -->
                        <tr class="bg-slate-100 text-slate-800 font-extrabold text-[10px] border-b border-slate-300 font-mono">
                            <!-- Monday -->
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">8:00-9:30</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">9:45-11:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">12:45-14:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-100 font-black text-sky-900">14:30-16:00</th>

                            <!-- Tuesday -->
                            <th class="py-2 px-1 border-r border-slate-300 bg-indigo-50/70">8:00-9:30</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-indigo-50/70">9:45-11:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-indigo-50/70">12:45-14:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-indigo-100 font-black text-indigo-900">14:30-16:00</th>

                            <!-- Wednesday -->
                            <th class="py-2 px-1 border-r border-slate-300 bg-slate-50">8:00-9:30</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-slate-50">9:45-11:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-slate-50">12:45-14:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-slate-200 font-black text-slate-900">14:30-16:00</th>

                            <!-- Thursday -->
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">8:00-9:30</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">9:45-11:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-50/70">12:45-14:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-sky-100 font-black text-sky-900">14:30-16:00</th>

                            <!-- Friday -->
                            <th class="py-2 px-1 border-r border-slate-300 bg-emerald-50/70">8:00-9:30</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-emerald-50/70">9:45-11:15</th>
                            <th class="py-2 px-1 border-r border-slate-300 bg-emerald-50/70">13:00-14:30</th>
                            <th class="py-2 px-1 bg-emerald-100 font-black text-emerald-900">14:45-16:00</th>
                        </tr>
                    </thead>

                    <!-- MATRIX BODY (CLASSES 1A-1H / 3A-3H) -->
                    <tbody class="divide-y divide-slate-200 font-mono font-medium">
                        @foreach($displayClasses as $clsIndex => $cls)
                            <tr class="hover:bg-sky-50/60 transition-colors {{ auth()->user()->kelas === $cls ? 'bg-amber-50 font-bold' : '' }}">
                                <!-- CLASS CODE ROW HEADER (Clickable to open dedicated class page) -->
                                <td class="py-3 px-2 border-r border-slate-200 bg-slate-50 text-slate-900 font-black text-xs align-middle">
                                    <a href="{{ route('mahasiswa.jadwal.kelas', $cls) }}" 
                                       title="Klik untuk membuka Page Dedicated Kelas {{ $cls }}"
                                       class="block py-1 rounded hover:bg-sky-600 hover:text-white transition-all group">
                                        <span class="block text-sm font-black text-slate-900 group-hover:text-white">{{ $cls }}</span>
                                        <span class="text-[9px] block text-slate-500 group-hover:text-white font-sans font-bold">
                                            @if(in_array($cls, ['1G', '1H', '3G', '3H']))
                                                (BM)
                                            @else
                                                (AP)
                                            @endif
                                        </span>
                                    </a>
                                </td>

                                <!-- 5 DAYS x 4 TIME SLOTS = 20 CELLS -->
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                    @php
                                        $slots = [
                                            ['start' => '08:00', 'end' => '09:30'],
                                            ['start' => '09:45', 'end' => '11:15'],
                                            ['start' => $hari === 'Jumat' ? '13:00' : '12:45', 'end' => $hari === 'Jumat' ? '14:30' : '14:15'],
                                            ['start' => $hari === 'Jumat' ? '14:45' : '14:30', 'end' => '16:00'],
                                        ];
                                    @endphp

                                    @foreach($slots as $s)
                                        @php
                                            $mapKey = $hari . '-' . $cls . '-' . $s['start'];
                                            $item = $jadwalMap[$mapKey] ?? null;
                                        @endphp
                                        <td class="p-1 border-r border-slate-200 align-middle min-w-[70px]">
                                            @if($item)
                                                @php
                                                    $nameParts = explode(' ', $item->dosen->name ?? 'Dosen BEC');
                                                    $initials = count($nameParts) >= 2 ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1)) : strtoupper(substr($nameParts[0], 0, 2));
                                                @endphp
                                                <div 
                                                    @click="selectedCell = {
                                                        subjectCode: '{{ $item->mataKuliah->kode_mk }}',
                                                        subjectName: '{{ addslashes($item->mataKuliah->name) }}',
                                                        dosenName: '{{ addslashes($item->dosen->name ?? '-') }}',
                                                        room: '{{ $item->ruangan }}',
                                                        hari: '{{ $hari }}',
                                                        time: '{{ $s['start'] }} - {{ $s['end'] }}',
                                                        kelas: '{{ $cls }}'
                                                    }"
                                                    class="cell-hover bg-sky-50/80 p-1.5 rounded-lg border border-sky-200 text-center cursor-pointer shadow-2xs group hover:border-sky-500 hover:bg-sky-600 hover:text-white"
                                                >
                                                    <!-- Subject Code (Top Line) -->
                                                    <span class="block font-black text-sky-900 group-hover:text-white text-[11px] leading-tight font-mono">
                                                        {{ $item->mataKuliah->kode_mk }}
                                                    </span>
                                                    <!-- Teacher Initials - Room Code (Bottom Line) -->
                                                    <span class="block text-[9px] text-slate-700 group-hover:text-sky-100 font-bold leading-tight truncate font-sans">
                                                        {{ $initials }} - {{ $item->ruangan }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-slate-300 text-[9px]">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PDF FOOTER REPLICA LEGEND BOX -->
            <div class="bg-slate-50 p-6 border-t border-slate-200 font-sans text-xs text-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Column 1: Teachers -->
                    <div class="space-y-2">
                        <div class="font-extrabold text-slate-900 uppercase border-b border-slate-200 pb-1 flex justify-between">
                            <span>Teachers</span>
                            <span class="text-[10px] text-slate-500 font-normal">Kode Dosen</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1 text-[11px] font-mono leading-tight max-h-56 overflow-y-auto hide-scrollbar">
                            <div><strong class="text-slate-900">AR</strong> Arwadi</div>
                            <div><strong class="text-slate-900">CA</strong> Candra Pradipta</div>
                            <div><strong class="text-slate-900">CP</strong> Chamirsyah Puspitasari</div>
                            <div><strong class="text-slate-900">DA</strong> Dian Ardiyansah</div>
                            <div><strong class="text-slate-900">SH</strong> Shoni Hamdani</div>
                            <div><strong class="text-slate-900">DV</strong> Deva Ardiansyah</div>
                            <div><strong class="text-slate-900">EH</strong> Ema Hermawati</div>
                            <div><strong class="text-slate-900">EM</strong> Eka Marindra Susilowati</div>
                            <div><strong class="text-slate-900">CS</strong> Chandra Sentosa</div>
                            <div><strong class="text-slate-900">MR</strong> Maulana Rahman</div>
                            <div><strong class="text-slate-900">LD</strong> Lindawati</div>
                            <div><strong class="text-slate-900">NA</strong> Nurul Af Idah Widyaningsih</div>
                            <div><strong class="text-slate-900">NH</strong> Nur Hanifah</div>
                            <div><strong class="text-slate-900">RF</strong> Rani Fidiyanti</div>
                            <div><strong class="text-slate-900">SK</strong> Sofyan Nata Komaradjaja</div>
                            <div><strong class="text-slate-900">SN</strong> Sidik Nurrohman</div>
                            <div><strong class="text-slate-900">WA</strong> Wiwi Alwiyah</div>
                            <div><strong class="text-slate-900">YE</strong> Yuli Ekawati</div>
                            <div><strong class="text-slate-900">YS</strong> Yuni Sutari</div>
                            <div><strong class="text-slate-900">TI</strong> Moedjiwati Sajid (Titiek)</div>
                            <div><strong class="text-slate-900">YF</strong> Yusuf Saefudin</div>
                            <div><strong class="text-slate-900">DS</strong> Deden Supriatna</div>
                        </div>
                    </div>

                    <!-- Column 2: Subjects -->
                    <div class="space-y-2">
                        <div class="font-extrabold text-slate-900 uppercase border-b border-slate-200 pb-1 flex justify-between">
                            <span>Subjects</span>
                            <span class="text-[10px] text-slate-500 font-normal">Mata Kuliah</span>
                        </div>
                        <div class="grid grid-cols-1 gap-1 text-[11px] font-mono leading-tight max-h-56 overflow-y-auto hide-scrollbar">
                            <div><strong class="text-slate-900">AI3</strong> Agama Islam III</div>
                            <div><strong class="text-slate-900">WOA2</strong> Writing for Office Admin II</div>
                            <div><strong class="text-slate-900">SOA2</strong> Speaking For Office Admin II</div>
                            <div><strong class="text-slate-900">AOE</strong> Applied Office Equipment</div>
                            <div><strong class="text-slate-900">TR2</strong> Translation II</div>
                            <div><strong class="text-slate-900">LOA2</strong> Listening for Office Admin II</div>
                            <div><strong class="text-slate-900">ROA2</strong> Reading for Office Admin II</div>
                            <div><strong class="text-slate-900">BST3</strong> Blind System Typing III</div>
                            <div><strong class="text-slate-900">DP3</strong> Data Processing III</div>
                            <div><strong class="text-slate-900">ARS</strong> Kearsipan</div>
                            <div><strong class="text-slate-900">KBI</strong> Korespondensi Bahasa Ind.</div>
                            <div><strong class="text-slate-900">AK2</strong> Akuntansi II</div>
                            <div><strong class="text-slate-900">WI2</strong> Web & Internet II</div>
                            <div><strong class="text-slate-900">GD</strong> Graphic design</div>
                            <div><strong class="text-slate-900">OETS</strong> Office Equip. Trouble Shooting</div>
                            <div><strong class="text-slate-900">EP</strong> Etika Profesi</div>
                            <div><strong class="text-slate-900">AB2</strong> Administrasi Bisnis II</div>
                            <div><strong class="text-slate-900">MK</strong> Manajemen Keuangan</div>
                            <div><strong class="text-slate-900">MRB</strong> Manajemen Resiko Bisnis</div>
                            <div><strong class="text-slate-900">IK</strong> Inovasi dan Kewirausahaan</div>
                            <div><strong class="text-slate-900">EB</strong> Etika Bisnis</div>
                            <div><strong class="text-slate-900">EKBO</strong> Evaluasi Kinerja Bisnis</div>
                            <div><strong class="text-slate-900">PBT2</strong> Proyek Bisnis Terintegrasi II</div>
                            <div><strong class="text-slate-900">CC2</strong> Content Creative II</div>
                            <div><strong class="text-slate-900">SP3</strong> Speaking III</div>
                            <div><strong class="text-slate-900">PB</strong> Presentation Building</div>
                            <div><strong class="text-slate-900">MHI</strong> Manajemen SDM & Hub. Ind.</div>
                            <div><strong class="text-slate-900">POR</strong> Perilaku Organisasi</div>
                            <div><strong class="text-slate-900">AM</strong> Affiliate Marketing</div>
                        </div>
                    </div>

                    <!-- Column 3: Rooms -->
                    <div class="space-y-2">
                        <div class="font-extrabold text-slate-900 uppercase border-b border-slate-200 pb-1 flex justify-between">
                            <span>Rooms</span>
                            <span class="text-[10px] text-slate-500 font-normal">Lokasi Ruang</span>
                        </div>
                        <div class="space-y-2 text-[11px] leading-snug">
                            <div>
                                <strong class="text-sky-700 block font-bold">1st STOREY:</strong>
                                <span class="text-slate-600">R.A (Assertive), R.B (Brave), R.C (Confident - Cinema Room), R.D (Diligent), R.E (Emphatic), R.F (Friendly), R.G (Generous)</span>
                            </div>
                            <div>
                                <strong class="text-indigo-700 block font-bold">2nd STOREY:</strong>
                                <span class="text-slate-600">R.I 1&2 (Innovative Lab), R.J (Jubilant), R.K (Keen Lab), R.L (Lovable), R.M (Merciful), R.N (Noble), R.O (Optimistic Lab 3)</span>
                            </div>
                            <div>
                                <strong class="text-emerald-700 block font-bold">3rd STOREY:</strong>
                                <span class="text-slate-600">R.P (Polite), R.Q (Quick), R.R (Rational), R.S (Saintly), R.T (Tender), R.U (Unique), R.V (Vigorous)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 4: Quick Navigation to Dedicated Class Pages -->
                    <div class="space-y-3 bg-white p-4 rounded-xl border border-slate-200 shadow-2xs">
                        <div class="font-black text-slate-900 uppercase border-b border-slate-200 pb-1 text-xs">
                            Buka Page Khusus Kelas
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium">Pilih kelas di bawah untuk melihat halaman dedicated jadwal kelas secara fokus:</p>

                        <div class="grid grid-cols-2 gap-2">
                            @foreach($displayClasses as $c)
                                <a href="{{ route('mahasiswa.jadwal.kelas', $c) }}" 
                                   class="px-2.5 py-1.5 bg-slate-100 hover:bg-sky-600 hover:text-white text-slate-900 font-extrabold rounded-lg text-center text-xs transition-all shadow-2xs flex items-center justify-between">
                                    <span>Kelas {{ $c }}</span>
                                    <span class="text-[10px]">→</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- EMPTY STATE JADWAL BELUM DIRILIS -->
        <div class="bg-white rounded-3xl p-10 sm:p-14 text-center border border-slate-200/90 shadow-sm space-y-4" data-aos="fade-up">
            <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mx-auto border border-sky-100 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="max-w-md mx-auto space-y-2">
                <h3 class="text-lg sm:text-xl font-black text-slate-900">Jadwal Perkuliahan Belum Dirilis</h3>
                <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                    Data pembagian kelas dan jadwal perkuliahan untuk Angkatan {{ $userAngkatan ?? 30 }} belum dipublikasikan oleh bagian akademik. Silakan tunggu informasi resmi selanjutnya.
                </p>
            </div>
        </div>
        @endif

        <!-- MODAL DETAIL CELL JADWAL (WHEN CELL CLICKED) -->
        <div x-show="selectedCell" style="display: none;" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4" @click.self="selectedCell = null" data-aos="zoom-in">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200 space-y-5">
                <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                    <div>
                        <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 font-mono text-xs font-black rounded-md" x-text="selectedCell?.subjectCode"></span>
                        <h3 class="text-xl font-black text-slate-900 mt-1" x-text="selectedCell?.subjectName"></h3>
                    </div>
                    <button @click="selectedCell = null" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-3 text-xs sm:text-sm">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-bold">Dosen Pengampu:</span>
                        <span class="font-extrabold text-slate-900" x-text="selectedCell?.dosenName"></span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-bold">Ruangan Perkuliahan:</span>
                        <span class="font-extrabold text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-lg border border-sky-100" x-text="selectedCell?.room"></span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-bold">Hari & Sesi Jam:</span>
                        <span class="font-mono font-bold text-slate-800" x-text="selectedCell?.hari + ', ' + selectedCell?.time + ' WIB'"></span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-slate-500 font-bold">Kelas:</span>
                        <span class="font-extrabold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-lg border border-indigo-100" x-text="'Kelas ' + selectedCell?.kelas"></span>
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <a :href="'/mahasiswa/jadwal/kelas/' + selectedCell?.kelas" class="w-full py-2.5 bg-sky-600 hover:bg-sky-500 text-white font-extrabold rounded-xl text-center text-xs transition-transform active:scale-95 shadow-md">
                        Buka Page Dedicated Kelas <span x-text="selectedCell?.kelas"></span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
