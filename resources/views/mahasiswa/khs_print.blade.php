<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi (KHS) - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            color: #111;
            margin: 0;
            padding: 24px;
            font-size: 13px;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 4px 0 0 0;
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }
        .info-grid td {
            padding: 3px 0;
        }
        .info-grid td.label {
            font-weight: bold;
            width: 130px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f0f0f0;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .summary-box {
            border: 2px solid #000;
            padding: 12px 20px;
            display: inline-block;
            float: right;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .footer-sign {
            margin-top: 60px;
            width: 100%;
            display: table;
        }
        .sign-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 70px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" style="background: #64748b; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; margin-left: 8px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h1>BOGOR EDUCARE</h1>
        <h2>KARTU HASIL STUDI (KHS) & TRANSKRIP NILAI SEMESENTARA</h2>
        <p style="margin: 4px 0 0 0; font-size: 11px;">Tahun Ajaran: {{ $user->tahun_ajaran ?? '2025/2026' }} | Angkatan {{ $user->angkatan ?? 29 }}</p>
    </div>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <table>
                    <tr><td class="label">Nama Mahasiswa</td><td>: <strong>{{ $user->name }}</strong></td></tr>
                    <tr><td class="label">NIM</td><td>: {{ $user->nim ?? '-' }}</td></tr>
                    <tr><td class="label">Jurusan</td><td>: {{ $user->jurusan ?? 'Administrasi Perkantoran' }}</td></tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table>
                    <tr><td class="label">Kelas</td><td>: {{ $user->kelas ?? '-' }}</td></tr>
                    <tr><td class="label">Angkatan</td><td>: Angkatan {{ $user->angkatan ?? 29 }}</td></tr>
                    <tr><td class="label">Cawu / Periode</td><td>: Cawu {{ $user->cawu ?? 1 }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Mata Kuliah / Evaluasi</th>
                <th class="text-center" style="width: 100px;">Tipe</th>
                <th class="text-center" style="width: 80px;">Nilai (0-100)</th>
                <th class="text-center" style="width: 80px;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($nilais as $n)
                @php
                    $score = $n->score ?? 0;
                    $grade = $score >= 85 ? 'A' : ($score >= 75 ? 'B' : ($score >= 65 ? 'C' : ($score >= 50 ? 'D' : 'E')));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $n->modul->mataKuliah->name ?? '-' }} ({{ $n->modul->title ?? 'Kuis' }})</td>
                    <td class="text-center">Kuis / Modul</td>
                    <td class="text-center"><strong>{{ $score }}</strong></td>
                    <td class="text-center"><strong>{{ $grade }}</strong></td>
                </tr>
            @empty
            @endforelse

            @forelse($tugasSubmissions as $sub)
                @php
                    $score = $sub->score ?? 0;
                    $grade = $score >= 85 ? 'A' : ($score >= 75 ? 'B' : ($score >= 65 ? 'C' : ($score >= 50 ? 'D' : 'E')));
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $sub->tugas->mataKuliah->name ?? '-' }} ({{ $sub->tugas->title ?? 'Tugas' }})</td>
                    <td class="text-center">Tugas Praktikum</td>
                    <td class="text-center"><strong>{{ $score }}</strong></td>
                    <td class="text-center"><strong>{{ $grade }}</strong></td>
                </tr>
            @empty
            @endforelse

            @if(count($nilais) === 0 && count($tugasSubmissions) === 0)
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #666;">Belum ada data nilai yang dinilai oleh dosen.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="width: 100%; overflow: hidden; margin-bottom: 30px;">
        <div class="summary-box">
            <strong>Rata-Rata Nilai:</strong> {{ $user->akumulasi_nilai }} / 100<br>
            <strong>Indeks Prestasi (IPK):</strong> <span style="font-size: 16px; color: #1e3a8a;"><strong>{{ number_format($user->ipk, 2) }}</strong></span> / 4.00
        </div>
    </div>

    <div class="footer-sign">
        <div class="sign-col">
            <p>Mengetahui,<br>Ketua Program Studi</p>
            <div class="sign-space"></div>
            <p>_______________________<br>NIDN. 0012093001</p>
        </div>
        <div class="sign-col">
            <p>Bogor, {{ date('d F Y') }}<br>Dosen Pembimbing Akademik</p>
            <div class="sign-space"></div>
            <p>_______________________<br>NIDN. 0012093002</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto trigger print after loading
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>
