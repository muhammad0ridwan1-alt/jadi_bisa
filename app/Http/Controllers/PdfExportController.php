<?php

namespace App\Http\Controllers;

use App\Models\BstScore;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\TugasSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfExportController extends Controller
{
    public function downloadKhs()
    {
        $user = Auth::user();
        $nilais = Nilai::where('mahasiswa_id', $user->id)
            ->with(['modul.mataKuliah'])
            ->get();
            
        $tugasSubmissions = TugasSubmission::where('mahasiswa_id', $user->id)
            ->with(['tugas.mataKuliah'])
            ->get();

        return view('mahasiswa.khs_print', compact('user', 'nilais', 'tugasSubmissions'));
    }

    public function downloadBstCertificate()
    {
        $user = Auth::user();
        $bestScore = BstScore::where('mahasiswa_id', $user->id)
            ->orderByDesc('cpm')
            ->first();

        if (!$bestScore) {
            return back()->with('error', 'Anda belum memiliki skor latihan mengetik (BST).');
        }

        return view('mahasiswa.bst_certificate_print', compact('user', 'bestScore'));
    }

    public function exportNilaiCsv($id)
    {
        $mataKuliah = MataKuliah::with(['moduls.nilais.mahasiswa', 'tugases.submissions.mahasiswa'])->findOrFail($id);
        if ($mataKuliah->dosen_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $filename = "Rekap_Nilai_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $mataKuliah->name) . "_" . date('Ymd') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($mataKuliah) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8 Excel support
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, ['No', 'NIM', 'Nama Mahasiswa', 'Jurusan', 'Kelas', 'Angkatan', 'Tipe Evaluasi', 'Judul Modul/Tugas', 'Nilai (0-100)', 'Status']);

            $no = 1;

            // Kuis / Modul Nilai
            foreach ($mataKuliah->moduls as $modul) {
                foreach ($modul->nilais as $nilai) {
                    $mhs = $nilai->mahasiswa;
                    if ($mhs) {
                        fputcsv($file, [
                            $no++,
                            $mhs->nim ?? '-',
                            $mhs->name,
                            $mhs->jurusan ?? '-',
                            $mhs->kelas ?? '-',
                            $mhs->angkatan ?? 29,
                            'Kuis / Modul',
                            $modul->title,
                            $nilai->score ?? 0,
                            $nilai->status ?? 'submitted'
                        ]);
                    }
                }
            }

            // Tugas Submissions
            foreach ($mataKuliah->tugases as $tugas) {
                foreach ($tugas->submissions as $sub) {
                    $mhs = $sub->mahasiswa;
                    if ($mhs) {
                        fputcsv($file, [
                            $no++,
                            $mhs->nim ?? '-',
                            $mhs->name,
                            $mhs->jurusan ?? '-',
                            $mhs->kelas ?? '-',
                            $mhs->angkatan ?? 29,
                            'Tugas Praktikum',
                            $tugas->title,
                            $sub->score ?? 0,
                            $sub->status ?? 'submitted'
                        ]);
                    }
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
