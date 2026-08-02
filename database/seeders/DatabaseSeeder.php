<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Modul;
use App\Models\Tugas;
use App\Models\Pengumuman;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Administrator BEC',
            'email' => 'admin@jadibisa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $dosen1 = User::create([
            'name' => 'Dr. Hendra Wijaya, M.Pd.',
            'email' => 'dosen@jadibisa.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        $dosen2 = User::create([
            'name' => 'Siti Rahmawati, S.E., M.M.',
            'email' => 'dosen2@jadibisa.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        $mhsAP = User::create([
            'name' => 'Mahasiswa AP',
            'email' => 'mahasiswa_ap@jadibisa.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'jurusan' => 'Administrasi Perkantoran',
            'nim' => '202610101',
            'semester' => 2,
        ]);

        $mhsBM = User::create([
            'name' => 'Mahasiswa BM',
            'email' => 'mahasiswa_bm@jadibisa.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'jurusan' => 'Bisnis Manajemen',
            'nim' => '202620101',
            'semester' => 2,
        ]);

        // 2. Create Mata Kuliahs for AP
        $mkAP1 = MataKuliah::create([
            'name' => 'Manajemen Perkantoran Modern',
            'kode_mk' => 'MK-AP-101',
            'jurusan' => 'Administrasi Perkantoran',
            'dosen_id' => $dosen1->id,
        ]);

        $mkAP2 = MataKuliah::create([
            'name' => 'Korespondensi & Kearsipan Digital',
            'kode_mk' => 'MK-AP-102',
            'jurusan' => 'Administrasi Perkantoran',
            'dosen_id' => $dosen1->id,
        ]);

        // Create Mata Kuliahs for BM
        $mkBM1 = MataKuliah::create([
            'name' => 'Dasar Pemasaran Digital & E-Commerce',
            'kode_mk' => 'MK-BM-201',
            'jurusan' => 'Bisnis Manajemen',
            'dosen_id' => $dosen2->id,
        ]);

        $mkBM2 = MataKuliah::create([
            'name' => 'Manajemen Keuangan Perusahaan',
            'kode_mk' => 'MK-BM-202',
            'jurusan' => 'Bisnis Manajemen',
            'dosen_id' => $dosen2->id,
        ]);

        // 3. Create Moduls inside Mata Kuliahs
        Modul::create([
            'dosen_id' => $dosen1->id,
            'mata_kuliah_id' => $mkAP1->id,
            'title' => 'Pertemuan 1: Konsep Dasar Tata Kelola Kantor Modern',
            'description' => 'Mempelajari prinsip dasar efisiensi kerja, tata ruang kantor, dan otomasi dokumen perkantoran.',
            'type' => 'materi',
            'youtube_link' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ]);

        Modul::create([
            'dosen_id' => $dosen1->id,
            'mata_kuliah_id' => $mkAP1->id,
            'title' => 'Kuis Pertemuan 1: Evaluasi Standar Operasional',
            'description' => 'Kerjakan kuis ini untuk menguji pemahaman Anda mengenai prinsip otomasi kantor.',
            'type' => 'kuis',
        ]);

        Modul::create([
            'dosen_id' => $dosen1->id,
            'mata_kuliah_id' => $mkAP2->id,
            'title' => 'Pertemuan 1: Teknik Kearsipan Elektronik',
            'description' => 'Panduan pengelolaan arsip secara terstruktur menggunakan cloud storage dan sistem indeksing.',
            'type' => 'materi',
        ]);

        Modul::create([
            'dosen_id' => $dosen2->id,
            'mata_kuliah_id' => $mkBM1->id,
            'title' => 'Pertemuan 1: Strategi Branding & Social Media Ads',
            'description' => 'Mempelajari pembuatan target pasar, strategi copy-writing, dan analisis metrik pemasaran.',
            'type' => 'materi',
            'youtube_link' => 'https://www.youtube.com/embed/dQw4w9WgWgQ',
        ]);

        Modul::create([
            'dosen_id' => $dosen2->id,
            'mata_kuliah_id' => $mkBM1->id,
            'title' => 'Kuis Pertemuan 1: Digital Marketing Fundamentals',
            'description' => 'Kuis pengujian wawasan digital marketing dasar.',
            'type' => 'kuis',
        ]);

        // 4. Create Tugases
        Tugas::create([
            'mata_kuliah_id' => $mkAP1->id,
            'dosen_id' => $dosen1->id,
            'title' => 'Tugas 1: Analisis Layout Ruang Kerja Komputer',
            'description' => 'Buat analisis layout tata ruang kantor minimal 2 halaman PDF dan berikan rekomendasi perbaikan.',
            'deadline' => now()->addDays(7),
        ]);

        Tugas::create([
            'mata_kuliah_id' => $mkBM1->id,
            'dosen_id' => $dosen2->id,
            'title' => 'Tugas 1: Perancangan Campaign Instagram Ads',
            'description' => 'Rancang konten campaign promosi produk lokal beserta alokasi anggaran.',
            'deadline' => now()->addDays(5),
        ]);

        // 5. Create Jadwals
        Jadwal::create([
            'mata_kuliah_id' => $mkAP1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'ruangan' => 'Lab Komputer A',
        ]);

        Jadwal::create([
            'mata_kuliah_id' => $mkAP2->id,
            'hari' => 'Rabu',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'ruangan' => 'R. Teoritis 2',
        ]);

        Jadwal::create([
            'mata_kuliah_id' => $mkBM1->id,
            'hari' => 'Selasa',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'ruangan' => 'Lab Bisnis B',
        ]);

        // 6. Create Pengumamans
        Pengumuman::create([
            'user_id' => $admin->id,
            'title' => 'Selamat Datang di Semester Baru 2026/2027!',
            'content' => 'Kepada seluruh mahasiswa BEC, pastikan Anda telah mengecek jadwal perkuliahan dan modul materi yang diunggah oleh Dosen Pengampu.',
            'target' => 'semua',
            'is_pinned' => true,
        ]);

        Pengumuman::create([
            'user_id' => $dosen1->id,
            'title' => 'Informasi Praktikum Administrasi Perkantoran',
            'content' => 'Mahasiswa AP wajib mengunggah file laporan praktikum tepat waktu sebelum tenggat deadline berakhir.',
            'target' => 'Administrasi Perkantoran',
            'is_pinned' => false,
        ]);
    }
}
