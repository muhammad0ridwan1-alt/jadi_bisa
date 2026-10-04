<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\BstScore;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\ModulProgress;
use App\Models\Pengumuman;
use App\Models\Angkatan;
use App\Models\KelasList;

class BecDataSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Disable foreign key checks for thorough cleanup
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Setup Angkatan: HANYA ANGKATAN 30 (Angkatan 29 Dihapus Sepenuhnya)
        Angkatan::where('nomor_angkatan', '!=', 30)->delete();
        Angkatan::updateOrCreate(
            ['nomor_angkatan' => 30],
            ['tahun_ajaran' => '2026/2027', 'is_active' => true]
        );

        // 2. Kosongkan seluruh data perkuliahan, kelas, kurikulum, dan jadwal
        // Sesuai permintaan user: dashboard admin kosong, dashboard dosen kosong tanpa matkul, dan halaman mahasiswa benar-benar kosong
        KelasList::truncate();
        MataKuliah::truncate();
        Jadwal::truncate();
        Modul::truncate();
        ModulProgress::truncate();
        Tugas::truncate();
        TugasSubmission::truncate();
        Nilai::truncate();
        BstScore::truncate();
        Pengumuman::truncate();

        // Kosongkan tabel absensi jika ada
        if (DB::getSchemaBuilder()->hasTable('absensi_details')) {
            DB::table('absensi_details')->truncate();
        }
        if (DB::getSchemaBuilder()->hasTable('absensis')) {
            DB::table('absensis')->truncate();
        }

        // 3. Hapus semua akun Mahasiswa (Kosongkan total)
        User::where('role', 'mahasiswa')->delete();

        // 4. Create / Ensure Administrator BEC
        User::updateOrCreate(
            ['email' => 'admin@jadibisa.com'],
            [
                'name' => 'Administrator BEC',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'jurusan' => 'Administrasi Perkantoran',
                'angkatan' => 30,
                'tahun_ajaran' => '2026/2027',
            ]
        );

        // 5. Data 22 Dosen BEC (Akun siap pakai, mata kuliah kosong untuk diatur admin)
        $dosensData = [
            ['code' => 'AR', 'name' => 'Arwadi'],
            ['code' => 'CA', 'name' => 'Candra Pradipta'],
            ['code' => 'CP', 'name' => 'Chamirsyah Puspitasari'],
            ['code' => 'DA', 'name' => 'Dian Ardiyansah'],
            ['code' => 'SH', 'name' => 'Shoni Hamdani'],
            ['code' => 'DV', 'name' => 'Deva Ardiansyah'],
            ['code' => 'EH', 'name' => 'Ema Hermawati'],
            ['code' => 'EM', 'name' => 'Eka Marindra Susilowati'],
            ['code' => 'CS', 'name' => 'Chandra Sentosa'],
            ['code' => 'MR', 'name' => 'Maulana Rahman'],
            ['code' => 'LD', 'name' => 'Lindawati'],
            ['code' => 'NA', 'name' => 'Nurul Af Idah Widyaningsih'],
            ['code' => 'NH', 'name' => 'Nur Hanifah'],
            ['code' => 'RF', 'name' => 'Rani Fidiyanti'],
            ['code' => 'SK', 'name' => 'Sofyan Nata Komaradjaja'],
            ['code' => 'SN', 'name' => 'Sidik Nurrohman'],
            ['code' => 'WA', 'name' => 'Wiwi Alwiyah'],
            ['code' => 'YE', 'name' => 'Yuli Ekawati'],
            ['code' => 'YS', 'name' => 'Yuni Sutari'],
            ['code' => 'TI', 'name' => 'Moedjiwati Sajid (Titiek)'],
            ['code' => 'YF', 'name' => 'Yusuf Saefudin'],
            ['code' => 'DS', 'name' => 'Deden Supriatna'],
        ];

        $validEmails = ['admin@jadibisa.com'];

        foreach ($dosensData as $d) {
            $codeClean = strtolower(str_replace([' ', '(', ')'], '', $d['code']));
            $email = $codeClean . '@jadibisa.com';
            $validEmails[] = $email;

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('password'),
                    'role' => 'dosen',
                    'jurusan' => 'Administrasi Perkantoran',
                    'cawu' => 1,
                    'angkatan' => 30,
                    'tahun_ajaran' => '2026/2027',
                    'kode_dosen' => $d['code'],
                    'mata_kuliah_diampu' => null, // Kosong, siap diatur oleh Admin
                    'sesi_per_kelas' => 2,        // Default 2 sesi per kelas (bisa diedit di admin)
                ]
            );
        }

        // Hapus user lain yang bukan admin dan bukan 22 dosen di atas
        User::whereNotIn('email', $validEmails)->delete();

        // Enable foreign key checks back
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
