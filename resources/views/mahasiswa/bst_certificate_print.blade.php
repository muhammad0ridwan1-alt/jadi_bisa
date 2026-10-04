<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Mengetik 10 Jari - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
            padding: 30px;
        }
        .cert-border {
            border: 10px solid #1e293b;
            padding: 10px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .cert-inner {
            border: 2px solid #cbd5e1;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .logo-title {
            font-size: 26px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        .sub-logo {
            font-size: 13px;
            font-family: 'Arial', sans-serif;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
        }
        .cert-header {
            font-size: 32px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }
        .cert-no {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 30px;
        }
        .given-to {
            font-size: 14px;
            font-style: italic;
            color: #475569;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 2px solid #1e3a8a;
            display: inline-block;
            padding-bottom: 4px;
            margin-bottom: 20px;
        }
        .cert-body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
            max-width: 650px;
            margin: 0 auto 30px auto;
        }
        .score-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            max-width: 500px;
            margin: 0 auto 30px auto;
            font-family: 'Arial', sans-serif;
        }
        .score-card {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 8px;
        }
        .score-val {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .score-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }
        .cert-footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-family: 'Arial', sans-serif;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-line {
            border-top: 1px solid #0f172a;
            margin-top: 60px;
            padding-top: 4px;
            font-weight: bold;
        }
        @media print {
            body { padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .cert-border { border-width: 6px; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak Sertifikat PDF
        </button>
        <button onclick="window.close()" style="background: #64748b; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; margin-left: 8px;">
            Tutup
        </button>
    </div>

    <div class="cert-border">
        <div class="cert-inner">
            <div class="logo-title">BOGOR EDUCARE</div>
            <div class="sub-logo">Business Education Center & Training Institute</div>

            <div class="cert-header">SERTIFIKAT KOMPETENSI</div>
            <div class="cert-no">Nomor: BEC/BST/{{ date('Y') }}/{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>

            <div class="given-to">Diberikan kepada:</div>
            <div class="student-name">{{ $user->name }}</div>

            <div class="cert-body">
                Telah berhasil menyelesaikan Uji Kompetensi <strong>Blind System Typing (BST / Mengetik 10 Jari)</strong> pada Program Studi <strong>{{ $user->jurusan ?? 'Administrasi Perkantoran' }}</strong> Angkatan {{ $user->angkatan ?? 29 }} Tahun Ajaran {{ $user->tahun_ajaran ?? '2025/2026' }} dengan capaian skor luar biasa:
            </div>

            <div class="score-grid">
                <div class="score-card">
                    <div class="score-val">{{ $bestScore->cpm }}</div>
                    <div class="score-label">CPM (Char/Min)</div>
                </div>
                <div class="score-card">
                    <div class="score-val">{{ $bestScore->wpm }}</div>
                    <div class="score-label">WPM (Words/Min)</div>
                </div>
                <div class="score-card">
                    <div class="score-val">{{ $bestScore->accuracy }}%</div>
                    <div class="score-label">Akurasi</div>
                </div>
            </div>

            <div class="cert-footer">
                <div style="text-align: left; font-size: 11px; color: #64748b;">
                    Diterbitkan: {{ date('d F Y') }}<br>
                    Verifikasi Digital: <strong>BEC-BST-VERIFIED-{{ strtoupper(substr(md5($user->id . $bestScore->cpm), 0, 8)) }}</strong>
                </div>
                <div class="sign-box">
                    <p style="font-size: 12px; margin-bottom: 0;">Instruktur / Kepala Lab BST</p>
                    <div class="sign-line">Bogor Educare</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>
