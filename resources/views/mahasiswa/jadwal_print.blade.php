<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEEKLY LESSON TIMETABLE SHEET - BOGOR EDUCARE</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 5mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #ffffff;
            color: #0f172a;
            margin: 0;
            padding: 10px;
            font-size: 8pt;
        }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0f172a;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        .toolbar button, .toolbar a {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .toolbar a.btn-back {
            background: #334155;
        }
        @media print {
            .toolbar {
                display: none !important;
            }
            body {
                padding: 0 !important;
            }
        }
        .header-sheet {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .header-sheet h1 {
            font-size: 14pt;
            font-weight: 900;
            margin: 0;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .header-sheet p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            color: #475569;
            font-weight: 600;
        }
        .meta-info {
            text-align: right;
            font-family: monospace;
            font-size: 8pt;
        }
        .meta-info strong {
            color: #0284c7;
        }
        table.timetable {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 6.5pt;
        }
        table.timetable th, table.timetable td {
            border: 1px solid #64748b;
            padding: 2px 1px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
        }
        table.timetable th {
            font-weight: 800;
        }
        .th-day-senin { background-color: #0c4a6e !important; color: #ffffff !important; }
        .th-day-selasa { background-color: #1e1b4b !important; color: #ffffff !important; }
        .th-day-rabu { background-color: #0f172a !important; color: #ffffff !important; }
        .th-day-kamis { background-color: #0c4a6e !important; color: #ffffff !important; }
        .th-day-jumat { background-color: #064e3b !important; color: #ffffff !important; }
        
        .th-time { background-color: #f1f5f9 !important; color: #1e293b !important; font-family: monospace; font-size: 6pt; }
        .row-class-header { background-color: #0284c7 !important; color: #ffffff !important; font-weight: 900; font-size: 7.5pt; }
        .row-bm-header { background-color: #d97706 !important; color: #ffffff !important; font-weight: 900; font-size: 7.5pt; }
        
        .cell-data {
            background-color: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
        }
        .cell-empty {
            background-color: #ffffff !important;
            color: #cbd5e1 !important;
        }
        .mk-title {
            font-weight: 800;
            color: #0369a1;
            display: block;
            line-height: 1.1;
        }
        .dosen-name {
            color: #334155;
            font-weight: 600;
            display: block;
            font-size: 5.5pt;
        }
        .ruangan-badge {
            color: #64748b;
            font-family: monospace;
            font-size: 5pt;
            display: block;
        }
    </style>
</head>
<body>

    <!-- Print / Export Floating Toolbar (Hidden when printing) -->
    <div class="toolbar">
        <div>
            <strong>Papan Jadwal Resmi Bogor EduCARE — Mode Cetak PDF</strong>
            <span style="font-size: 10px; color: #94a3b8; display: block;">Format otomatis diset ke A4 Landscape. Klik tombol di kanan untuk mengunduh PDF.</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('mahasiswa.jadwal') }}" class="btn-back">← Kembali ke Aplikasi</a>
            <button onclick="window.print()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan sebagai PDF
            </button>
        </div>
    </div>

    <!-- Printable Official Sheet Header -->
    <div class="header-sheet">
        <div>
            <h1>BOGOR EDUCARE — WEEKLY LESSON TIMETABLE SHEET</h1>
            <p>Batch {{ $userAngkatan ?? auth()->user()->angkatan ?? 30 }} (Cawu {{ auth()->user()->cawu ?? 1 }}) | Periode: TA {{ auth()->user()->tahun_ajaran ?? '2026/2027' }}</p>
        </div>
        <div class="meta-info">
            <strong>OFFICIAL TIMETABLE SHEET</strong><br>
            <span>Tanggal Cetak: {{ date('d M Y') }}</span>
        </div>
    </div>

    <!-- Matrix Table Board -->
    <table class="timetable">
        <thead>
            <tr>
                <th rowspan="2" style="width: 45px; background: #0f172a; color: #ffffff;">Kelas / Jam</th>
                <th colspan="4" class="th-day-senin">Monday (Senin)</th>
                <th colspan="4" class="th-day-selasa">Tuesday (Selasa)</th>
                <th colspan="4" class="th-day-rabu">Wednesday (Rabu)</th>
                <th colspan="4" class="th-day-kamis">Thursday (Kamis)</th>
                <th colspan="4" class="th-day-jumat">Friday (Jumat)</th>
            </tr>
            <tr class="th-time">
                <!-- Senin -->
                <th>8:00-9:30</th><th>9:45-11:15</th><th>12:45-14:15</th><th>14:30-16:00</th>
                <!-- Selasa -->
                <th>8:00-9:30</th><th>9:45-11:15</th><th>12:45-14:15</th><th>14:30-16:00</th>
                <!-- Rabu -->
                <th>8:00-9:30</th><th>9:45-11:15</th><th>12:45-14:15</th><th>14:30-16:00</th>
                <!-- Kamis -->
                <th>8:00-9:30</th><th>9:45-11:15</th><th>12:45-14:15</th><th>14:30-16:00</th>
                <!-- Jumat -->
                <th>8:00-9:30</th><th>9:45-11:15</th><th>12:45-14:15</th><th>14:30-16:00</th>
            </tr>
        </thead>
        <tbody>
            @php
                $classesList = $classes ?? [];
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                $timeSlots = ['08:00', '09:45', '12:45', '14:30'];
            @endphp

            @forelse($classesList as $c)
                @php $isBM = in_array($c, ['1G', '1H', '3G', '3H']); @endphp
                <tr>
                    <td class="{{ $isBM ? 'row-bm-header' : 'row-class-header' }}">
                        {{ $c }}
                    </td>

                    @foreach($days as $hari)
                        @foreach($timeSlots as $jam)
                            @php
                                $mapKey = $hari . '-' . $c . '-' . $jam;
                                $item = $jadwalMap[$mapKey] ?? null;
                            @endphp

                            @if($item)
                                <td class="cell-data">
                                    <span class="mk-title">{{ $item->mataKuliah->name }}</span>
                                    <span class="dosen-name">{{ $item->dosen->name ?? '-' }}</span>
                                    <span class="ruangan-badge">[{{ $item->ruangan }}]</span>
                                </td>
                            @else
                                <td class="cell-empty">-</td>
                            @endif
                        @endforeach
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="21" style="padding: 40px; text-align: center; color: #64748b; font-size: 13px; font-weight: bold;">
                        Jadwal perkuliahan dan pembagian kelas untuk Angkatan {{ $userAngkatan ?? 30 }} belum dirilis.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        // Auto trigger print dialog after page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
