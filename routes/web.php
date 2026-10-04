<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PdfExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\Pengumuman;
use App\Models\Jadwal;
use App\Models\Angkatan;
use App\Models\KelasList;

// Include global helper functions
require_once app_path('Helpers/helpers.php');

// Landing Page
Route::get('/', function () {
    $mahasiswaCount = User::where('role', 'mahasiswa')->count();
    $modulCount = Modul::where('type', 'materi')->count();
    $quizCount = Modul::where('type', 'kuis')->count();

    return view('welcome', compact('mahasiswaCount', 'modulCount', 'quizCount'));
});

// Central Dashboard Redirect
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'dosen') return redirect()->route('dosen.dashboard');
    
    if ($user->jurusan === 'Administrasi Perkantoran') {
        return redirect()->route('mahasiswa.dashboard_ap');
    } else {
        return redirect()->route('mahasiswa.dashboard_bm');
    }
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// 🔴 ADMIN ROUTES (Guarded by role:admin)
// ==========================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Admin Master Angkatan Management
    Route::get('/angkatan', function () {
        $angkatans = Angkatan::orderBy('nomor_angkatan', 'desc')->get();
        return view('admin.angkatan', compact('angkatans'));
    })->name('angkatan');

    Route::post('/angkatan', function (Request $request) {
        $request->validate([
            'nomor_angkatan' => 'required|integer|unique:angkatans,nomor_angkatan',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        Angkatan::create([
            'nomor_angkatan' => (int)$request->nomor_angkatan,
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_active' => true,
        ]);

        return back()->with('success', 'Angkatan ' . $request->nomor_angkatan . ' (' . $request->tahun_ajaran . ') berhasil ditambahkan!');
    })->name('angkatan.store');

    Route::delete('/angkatan/{id}', function ($id) {
        $ang = Angkatan::findOrFail($id);
        $ang->delete();
        return back()->with('success', 'Data Angkatan berhasil dihapus.');
    })->name('angkatan.destroy');

    // Admin Master Kelas Management per Angkatan
    Route::get('/kelas-management', function (Request $request) {
        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $selectedAngkatan = (int)$request->query('angkatan', $defaultAngkatan);
        $allAngkatans = Angkatan::orderBy('nomor_angkatan', 'desc')->get();
        $kelases = KelasList::where('angkatan', $selectedAngkatan)->orderBy('name')->get();
        return view('admin.kelas_management', compact('kelases', 'allAngkatans', 'selectedAngkatan'));
    })->name('kelas_management');

    Route::post('/kelas-management', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen',
            'angkatan' => 'required|integer',
        ]);

        KelasList::create([
            'name' => trim($request->name),
            'jurusan' => $request->jurusan,
            'angkatan' => (int)$request->angkatan,
        ]);

        return back()->with('success', 'Kelas ' . $request->name . ' untuk Angkatan ' . $request->angkatan . ' berhasil ditambahkan!');
    })->name('kelas_management.store');

    Route::put('/kelas-management/{id}', function (Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen',
            'angkatan' => 'required|integer',
        ]);

        $k = KelasList::findOrFail($id);
        $k->update([
            'name' => trim($request->name),
            'jurusan' => $request->jurusan,
            'angkatan' => (int)$request->angkatan,
        ]);

        return back()->with('success', 'Kelas ' . $k->name . ' berhasil diperbarui!');
    })->name('kelas_management.update');

    Route::delete('/kelas-management/{id}', function ($id) {
        $k = KelasList::findOrFail($id);
        $k->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    })->name('kelas_management.destroy');
    
    Route::get('/users/{role}', function (Request $request, $role) {
        if (!in_array($role, ['mahasiswa', 'dosen'])) abort(404);
        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $selectedAngkatan = (int)$request->query('angkatan', $defaultAngkatan);
        $allAngkatans = Angkatan::orderBy('nomor_angkatan', 'desc')->get();

        $query = User::where('role', $role);
        if ($role === 'mahasiswa') {
            $query->where('angkatan', $selectedAngkatan);
        }
        $users = $query->get();
        $availableClasses = KelasList::where('angkatan', $selectedAngkatan)->pluck('name')->toArray();

        return view('admin.users', compact('users', 'role', 'selectedAngkatan', 'allAngkatans', 'availableClasses'));
    })->name('users');

    Route::put('/users/{id}', function (Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jurusan' => 'nullable|string',
            'kelas' => 'nullable|string',
            'cawu' => 'nullable|integer|min:1|max:3',
            'angkatan' => 'nullable|integer',
            'tahun_ajaran' => 'nullable|string',
            'nim' => 'nullable|string',
            'kode_dosen' => 'nullable|string|max:50',
            'mata_kuliah_diampu' => 'nullable|string|max:255',
            'sesi_per_kelas' => 'nullable|integer|min:1|max:20',
            'phone' => 'nullable|string|max:50',
        ]);

        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'jurusan' => $request->has('jurusan') ? $request->jurusan : $user->jurusan,
        ];

        if ($user->role === 'dosen') {
            $updateData['kode_dosen'] = $request->input('kode_dosen');
            $updateData['mata_kuliah_diampu'] = $request->input('mata_kuliah_diampu');
            $updateData['sesi_per_kelas'] = $request->filled('sesi_per_kelas') ? (int)$request->sesi_per_kelas : ($user->sesi_per_kelas ?? 1);
            if ($request->has('phone')) {
                $updateData['phone'] = $request->phone;
            }
        } else {
            $updateData['kelas'] = $request->has('kelas') ? $request->kelas : $user->kelas;
            $updateData['cawu'] = $request->cawu ? (int)$request->cawu : $user->cawu;
            $updateData['angkatan'] = $request->angkatan ? (int)$request->angkatan : ($user->angkatan ?? $defaultAngkatan);
            $updateData['tahun_ajaran'] = $request->tahun_ajaran ?: ($user->tahun_ajaran ?? '2026/2027');
            $updateData['nim'] = $request->has('nim') ? $request->nim : $user->nim;
        }

        $user->update($updateData);

        $targetRole = in_array($user->role, ['mahasiswa', 'dosen']) ? $user->role : 'mahasiswa';
        return redirect()->route('admin.users', ['role' => $targetRole, 'angkatan' => $user->angkatan ?? $defaultAngkatan])->with('success', 'Data ' . $user->name . ' berhasil diperbarui!');
    })->name('users.update');

    // Impersonate / Masuk Sebagai Pengguna (Mahasiswa / Dosen)
    Route::post('/impersonate/{id}', function ($id) {
        $targetUser = User::findOrFail($id);
        session(['impersonator_id' => Auth::id()]);
        Auth::login($targetUser);

        if ($targetUser->role === 'dosen') {
            return redirect()->route('dosen.dashboard')->with('success', 'Beralih melihat sebagai Dosen: ' . $targetUser->name);
        } elseif ($targetUser->jurusan === 'Bisnis Manajemen') {
            return redirect()->route('mahasiswa.dashboard_bm')->with('success', 'Beralih melihat sebagai Mahasiswa: ' . $targetUser->name);
        } else {
            return redirect()->route('mahasiswa.dashboard_ap')->with('success', 'Beralih melihat sebagai Mahasiswa: ' . $targetUser->name);
        }
    })->name('impersonate');
    
    Route::delete('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->route('admin.users', ['role' => 'mahasiswa'])->with('error', 'Tidak dapat menghapus sesama admin.');
        }
        $targetRole = $user->role;
        $user->delete();
        return redirect()->route('admin.users', ['role' => $targetRole])->with('success', 'Pengguna berhasil dihapus.');
    })->name('users.destroy');

    // Admin Master Mata Kuliah & Dosen Pengampu Management
    Route::get('/matkul', function (Request $request) {
        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $selectedAngkatan = (int)$request->query('angkatan', $defaultAngkatan);
        $allAngkatans = Angkatan::orderBy('nomor_angkatan', 'desc')->get();
        $mataKuliahs = MataKuliah::where('angkatan', $selectedAngkatan)->with('dosen')->orderBy('name')->get();
        $dosens = User::where('role', 'dosen')->orderBy('name')->get();
        return view('admin.matkul', compact('mataKuliahs', 'dosens', 'selectedAngkatan', 'allAngkatans'));
    })->name('matkul');

    Route::post('/matkul', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_mk' => 'required|string|max:255|unique:mata_kuliahs',
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen',
            'cawu' => 'required|integer|min:1|max:3',
            'angkatan' => 'nullable|integer',
            'tahun_ajaran' => 'nullable|string',
            'dosen_id' => 'nullable|exists:users,id',
        ]);

        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $defaultTahun = Angkatan::where('is_active', true)->value('tahun_ajaran') ?? '2026/2027';

        MataKuliah::create([
            'name' => $request->name,
            'kode_mk' => $request->kode_mk,
            'jurusan' => $request->jurusan,
            'cawu' => $request->cawu,
            'angkatan' => $request->angkatan ? (int)$request->angkatan : $defaultAngkatan,
            'tahun_ajaran' => $request->tahun_ajaran ?: $defaultTahun,
            'dosen_id' => $request->dosen_id ?: null,
        ]);

        return back()->with('success', 'Mata Kuliah baru berhasil ditambahkan!');
    })->name('matkul.store');

    Route::put('/matkul/{id}', function (Request $request, $id) {
        $mk = MataKuliah::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_mk' => 'required|string|max:255|unique:mata_kuliahs,kode_mk,' . $id,
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen',
            'cawu' => 'required|integer|min:1|max:3',
            'angkatan' => 'nullable|integer',
            'tahun_ajaran' => 'nullable|string',
            'dosen_id' => 'nullable|exists:users,id',
        ]);

        $mk->update([
            'name' => $request->name,
            'kode_mk' => $request->kode_mk,
            'jurusan' => $request->jurusan,
            'cawu' => $request->cawu,
            'angkatan' => $request->angkatan ? (int)$request->angkatan : $mk->angkatan,
            'tahun_ajaran' => $request->tahun_ajaran ?: $mk->tahun_ajaran,
            'dosen_id' => $request->dosen_id ?: null,
        ]);

        return back()->with('success', 'Mata Kuliah berhasil diperbarui!');
    })->name('matkul.update');

    Route::delete('/matkul/{id}', function ($id) {
        $mk = MataKuliah::findOrFail($id);
        $mk->delete();
        return back()->with('success', 'Mata Kuliah berhasil dihapus!');
    })->name('matkul.destroy');

    // Admin Timetable Schedule & Cawu Management
    Route::get('/jadwal', function (Request $request) {
        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $selectedAngkatan = (int)$request->query('angkatan', $defaultAngkatan);
        $allAngkatans = Angkatan::orderBy('nomor_angkatan', 'desc')->get();
        
        $dbClasses = KelasList::where('angkatan', $selectedAngkatan)->pluck('name')->toArray();
        if (count($dbClasses) === 0) {
            $dbClasses = Jadwal::where('angkatan', $selectedAngkatan)->distinct()->pluck('kelas')->toArray();
        }
        $allClasses = array_unique($dbClasses);
        
        $selectedKelas = $request->query('kelas', count($allClasses) > 0 ? reset($allClasses) : 'semua');

        $query = Jadwal::with(['mataKuliah', 'dosen'])->where('angkatan', $selectedAngkatan);
        if ($selectedKelas !== 'semua' && $selectedKelas !== '') {
            $query->where('kelas', $selectedKelas);
        }
        $jadwals = $query->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")
            ->orderBy('jam_mulai')
            ->get();

        $mataKuliahs = MataKuliah::where('angkatan', $selectedAngkatan)->orderBy('name')->get();
        if ($mataKuliahs->count() === 0) {
            $mataKuliahs = MataKuliah::orderBy('name')->get();
        }
        $dosens = User::where('role', 'dosen')->orderBy('name')->get();

        return view('admin.jadwal', compact('jadwals', 'selectedKelas', 'selectedAngkatan', 'allAngkatans', 'mataKuliahs', 'dosens', 'allClasses'));
    })->name('jadwal');

    Route::post('/jadwal', function (Request $request) {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'nullable|exists:users,id',
            'kelas' => 'required|string',
            'angkatan' => 'nullable|integer',
            'tahun_ajaran' => 'nullable|string',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string',
        ]);

        $defaultAngkatan = Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30;
        $defaultTahun = Angkatan::where('is_active', true)->value('tahun_ajaran') ?? '2026/2027';

        Jadwal::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id ?: null,
            'kelas' => trim($request->kelas),
            'angkatan' => $request->angkatan ? (int)$request->angkatan : $defaultAngkatan,
            'tahun_ajaran' => $request->tahun_ajaran ?: $defaultTahun,
            'hari' => $request->hari,
            'jam_mulai' => strlen($request->jam_mulai) === 5 ? $request->jam_mulai . ':00' : $request->jam_mulai,
            'jam_selesai' => strlen($request->jam_selesai) === 5 ? $request->jam_selesai . ':00' : $request->jam_selesai,
            'ruangan' => $request->ruangan,
        ]);

        return redirect()->route('admin.jadwal', ['kelas' => $request->kelas, 'angkatan' => $request->angkatan ?: $defaultAngkatan])->with('success', 'Jadwal perkuliahan berhasil ditambahkan!');
    })->name('jadwal.store');

    Route::put('/jadwal/{id}', function (Request $request, $id) {
        $jadwal = Jadwal::findOrFail($id);
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'nullable|exists:users,id',
            'kelas' => 'required|string',
            'angkatan' => 'nullable|integer',
            'tahun_ajaran' => 'nullable|string',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required|string',
        ]);

        $jadwal->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id ?: null,
            'kelas' => trim($request->kelas),
            'angkatan' => $request->angkatan ? (int)$request->angkatan : $jadwal->angkatan,
            'tahun_ajaran' => $request->tahun_ajaran ?: $jadwal->tahun_ajaran,
            'hari' => $request->hari,
            'jam_mulai' => strlen($request->jam_mulai) === 5 ? $request->jam_mulai . ':00' : $request->jam_mulai,
            'jam_selesai' => strlen($request->jam_selesai) === 5 ? $request->jam_selesai . ':00' : $request->jam_selesai,
            'ruangan' => $request->ruangan,
        ]);

        return redirect()->route('admin.jadwal', ['kelas' => $request->kelas, 'angkatan' => $jadwal->angkatan])->with('success', 'Jadwal perkuliahan berhasil diperbarui!');
    })->name('jadwal.update');

    Route::delete('/jadwal/{id}', function ($id) {
        $jadwal = Jadwal::findOrFail($id);
        $targetKelas = $jadwal->kelas ?: '3A';
        $jadwal->delete();
        return redirect()->route('admin.jadwal', ['kelas' => $targetKelas])->with('success', 'Jadwal perkuliahan berhasil dihapus!');
    })->name('jadwal.destroy');

    Route::get('/pengumuman', function () {
        $pengumumans = Pengumuman::with('author')->latest()->get();
        return view('admin.pengumuman', compact('pengumumans'));
    })->name('pengumuman');

    Route::post('/pengumuman', function (Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:semua,Administrasi Perkantoran,Bisnis Manajemen',
        ]);

        Pengumuman::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return back()->with('success', 'Pengumuman resmi berhasil diterbitkan!');
    })->name('pengumuman.store');

    Route::delete('/pengumuman/{id}', function ($id) {
        $p = Pengumuman::findOrFail($id);
        $p->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    })->name('pengumuman.destroy');
});


// ==========================================
// 🟢 DOSEN ROUTES (Guarded by role:dosen)
// ==========================================
Route::middleware(['auth', 'verified', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', function () { 
        $mataKuliahs = MataKuliah::where('dosen_id', Auth::id())->with(['moduls', 'tugases'])->get();
        $moduls = Modul::where('dosen_id', Auth::id())->with('mataKuliah')->latest()->get();
        return view('dosen.dashboard', compact('mataKuliahs', 'moduls')); 
    })->name('dashboard');

    Route::get('/kelas/{id}', function ($id) {
        $mataKuliah = MataKuliah::where('dosen_id', Auth::id())->with(['moduls', 'tugases.submissions'])->findOrFail($id);
        return view('dosen.kelas_show', compact('mataKuliah'));
    })->name('kelas.show');

    Route::post('/kelas', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_mk' => 'required|string|max:255|unique:mata_kuliahs',
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen'
        ]);

        MataKuliah::create([
            'name' => $request->name,
            'kode_mk' => $request->kode_mk,
            'jurusan' => $request->jurusan,
            'dosen_id' => Auth::id()
        ]);

        return back()->with('success', 'Kelas baru berhasil dibuat!');
    })->name('kelas.store');

    Route::put('/kelas/{id}', function (Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_mk' => 'required|string|max:255|unique:mata_kuliahs,kode_mk,' . $id,
            'jurusan' => 'required|in:Administrasi Perkantoran,Bisnis Manajemen'
        ]);

        $mk = MataKuliah::where('dosen_id', Auth::id())->findOrFail($id);
        $mk->update([
            'name' => $request->name,
            'kode_mk' => $request->kode_mk,
            'jurusan' => $request->jurusan,
        ]);

        return back()->with('success', 'Kelas berhasil diperbarui!');
    })->name('kelas.update');

    Route::delete('/kelas/{id}', function ($id) {
        $mk = MataKuliah::where('dosen_id', Auth::id())->findOrFail($id);
        $mk->delete();
        return back()->with('success', 'Kelas berhasil dihapus!');
    })->name('kelas.destroy');

    // Modul CRUD
    Route::post('/modul', function (Request $request) {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:materi,kuis',
            'file_path' => 'nullable|file|mimes:pdf|max:10240',
            'youtube_link' => 'nullable|url'
        ]);

        $mk = MataKuliah::where('dosen_id', Auth::id())->findOrFail($request->mata_kuliah_id);

        $path = null;
        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('moduls', 'public');
        }

        Modul::create([
            'dosen_id' => Auth::id(),
            'mata_kuliah_id' => $mk->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'file_path' => $path,
            'youtube_link' => formatYoutubeEmbed($request->youtube_link)
        ]);

        return back()->with('success', 'Modul berhasil ditambahkan!');
    })->name('modul.store');

    Route::delete('/modul/{id}', function ($id) {
        $modul = Modul::where('dosen_id', Auth::id())->findOrFail($id);
        $modul->delete();
        return back()->with('success', 'Modul berhasil dihapus!');
    })->name('modul.destroy');

    // Tugas CRUD
    Route::post('/tugas', function (Request $request) {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'file_path' => 'nullable|file|max:10240',
        ]);

        $mk = MataKuliah::where('dosen_id', Auth::id())->findOrFail($request->mata_kuliah_id);

        $path = null;
        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('tugases', 'public');
        }

        Tugas::create([
            'mata_kuliah_id' => $mk->id,
            'dosen_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Tugas praktikum baru berhasil dipublikasikan!');
    })->name('tugas.store');

    Route::delete('/tugas/{id}', function ($id) {
        $tugas = Tugas::where('dosen_id', Auth::id())->findOrFail($id);
        $tugas->delete();
        return back()->with('success', 'Tugas berhasil dihapus.');
    })->name('tugas.destroy');

    // Grading Kuis & Tugas
    Route::get('/modul/{id}/penilaian', function ($id) {
        $modul = Modul::where('dosen_id', Auth::id())->findOrFail($id);
        $nilais = Nilai::where('modul_id', $id)->with('mahasiswa')->get();
        return view('dosen.penilaian', compact('modul', 'nilais'));
    })->name('modul.penilaian');

    Route::post('/nilai/{nilai_id}', function (Request $request, $nilai_id) {
        $request->validate(['score' => 'required|numeric|min:0|max:100']);
        $nilai = Nilai::findOrFail($nilai_id);
        if ($nilai->modul->dosen_id !== Auth::id()) abort(403);

        $nilai->update([
            'score' => $request->score,
            'status' => 'graded'
        ]);
        return back()->with('success', 'Nilai kuis berhasil disimpan!');
    })->name('nilai.update');

    Route::post('/tugas-submission/{id}/grade', function (Request $request, $id) {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);
        $sub = TugasSubmission::findOrFail($id);
        if ($sub->tugas->dosen_id !== Auth::id()) abort(403);

        $sub->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => 'graded'
        ]);
        return back()->with('success', 'Nilai tugas mahasiswa berhasil diperbarui!');
    })->name('tugas_submission.grade');

    // Pusat Modul & Materi
    Route::get('/modul', function () {
        $mataKuliahs = MataKuliah::where('dosen_id', Auth::id())->get();
        $moduls = Modul::where('dosen_id', Auth::id())->with('mataKuliah')->latest()->get();
        return view('dosen.modul_index', compact('mataKuliahs', 'moduls'));
    })->name('modul');

    // Pusat Tugas & Praktikum
    Route::get('/tugas', function () {
        $mataKuliahs = MataKuliah::where('dosen_id', Auth::id())->get();
        $tugases = Tugas::where('dosen_id', Auth::id())->with(['mataKuliah', 'submissions.mahasiswa'])->latest()->get();
        return view('dosen.tugas_index', compact('mataKuliahs', 'tugases'));
    })->name('tugas');

    // Pusat Penilaian & Rekap Nilai
    Route::get('/penilaian', function () {
        $mataKuliahs = MataKuliah::where('dosen_id', Auth::id())->with(['moduls', 'tugases'])->get();
        $modulIds = Modul::where('dosen_id', Auth::id())->pluck('id');
        $tugasIds = Tugas::where('dosen_id', Auth::id())->pluck('id');

        $nilais = Nilai::whereIn('modul_id', $modulIds)->with(['modul.mataKuliah', 'mahasiswa'])->latest()->get();
        $tugasSubmissions = TugasSubmission::whereIn('tugas_id', $tugasIds)->with(['tugas.mataKuliah', 'mahasiswa'])->latest()->get();

        return view('dosen.penilaian_index', compact('mataKuliahs', 'nilais', 'tugasSubmissions'));
    })->name('penilaian');

    // Jadwal Mengajar Dosen
    Route::get('/jadwal', function () {
        $jadwals = Jadwal::where('dosen_id', Auth::id())
            ->with('mataKuliah')
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")
            ->orderBy('jam_mulai')
            ->get();
        return view('dosen.jadwal', compact('jadwals'));
    })->name('jadwal');

    // Direktori Mahasiswa per Kelas Dosen
    Route::get('/mahasiswa', function (Request $request) {
        $jadwals = Jadwal::where('dosen_id', Auth::id())->get();
        $kelasDosen = $jadwals->pluck('kelas')->unique()->filter()->values()->toArray();

        $selectedKelas = $request->query('kelas', count($kelasDosen) > 0 ? $kelasDosen[0] : 'semua');

        $query = User::where('role', 'mahasiswa');
        if ($selectedKelas !== 'semua') {
            $query->where('kelas', $selectedKelas);
        } elseif (count($kelasDosen) > 0) {
            $query->whereIn('kelas', $kelasDosen);
        }

        $mahasiswas = $query->orderBy('name')->get();
        return view('dosen.mahasiswa', compact('mahasiswas', 'kelasDosen', 'selectedKelas'));
    })->name('mahasiswa');

    // Pengumuman Dosen
    Route::get('/pengumuman', function () {
        $pengumumans = Pengumuman::where('user_id', Auth::id())->latest()->get();
        return view('dosen.pengumuman', compact('pengumumans'));
    })->name('pengumuman');

    Route::post('/pengumuman', function (Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:semua,Administrasi Perkantoran,Bisnis Manajemen',
        ]);

        Pengumuman::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'target' => $request->target,
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return back()->with('success', 'Pengumuman dosen berhasil dipublikasikan!');
    })->name('pengumuman.store');

    Route::delete('/pengumuman/{id}', function ($id) {
        $p = Pengumuman::where('user_id', Auth::id())->findOrFail($id);
        $p->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    })->name('pengumuman.destroy');

    // Export Rekap Nilai CSV
    Route::get('/kelas/{id}/export-nilai', [PdfExportController::class, 'exportNilaiCsv'])->name('kelas.export_nilai');
});


// ==========================================
// 🔵 MAHASISWA ROUTES (Guarded by role:mahasiswa)
// ==========================================
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard/ap', function () { 
        if (Auth::user()->jurusan !== 'Administrasi Perkantoran') {
            return redirect()->route('mahasiswa.dashboard_bm');
        }
        $query = MataKuliah::where('jurusan', 'Administrasi Perkantoran');
        if (Auth::user()->angkatan) {
            $query->where('angkatan', Auth::user()->angkatan);
        }
        if (Auth::user()->cawu) {
            $query->where('cawu', Auth::user()->cawu);
        }
        $mataKuliahs = $query->with(['dosen', 'moduls', 'tugases'])
                        ->withCount(['moduls', 'tugases'])
                        ->get();
        return view('mahasiswa.dashboard_ap', compact('mataKuliahs')); 
    })->name('dashboard_ap');

    Route::get('/dashboard/bm', function () { 
        if (Auth::user()->jurusan !== 'Bisnis Manajemen') {
            return redirect()->route('mahasiswa.dashboard_ap');
        }
        $query = MataKuliah::where('jurusan', 'Bisnis Manajemen');
        if (Auth::user()->angkatan) {
            $query->where('angkatan', Auth::user()->angkatan);
        }
        if (Auth::user()->cawu) {
            $query->where('cawu', Auth::user()->cawu);
        }
        $mataKuliahs = $query->with(['dosen', 'moduls', 'tugases'])
                        ->withCount(['moduls', 'tugases'])
                        ->get();
        return view('mahasiswa.dashboard_bm', compact('mataKuliahs')); 
    })->name('dashboard_bm');

    // Mata Kuliah Detail (Card Click -> View Modules Inside)
    Route::get('/matkul/{id}', function ($id) {
        $mataKuliah = MataKuliah::with(['dosen', 'moduls', 'tugases'])->findOrFail($id);
        if ($mataKuliah->jurusan !== Auth::user()->jurusan) {
            abort(403, 'Anda tidak memiliki akses ke mata kuliah jurusan lain.');
        }
        return view('mahasiswa.matkul_show', compact('mataKuliah'));
    })->name('matkul.show');

    // Single Modul/Quiz View
    Route::get('/modul/{id}', function ($id) {
        $modul = Modul::with('mataKuliah', 'dosen')->findOrFail($id);
        if ($modul->mataKuliah->jurusan !== Auth::user()->jurusan) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $nilai = Nilai::where('mahasiswa_id', Auth::id())
                    ->where('modul_id', $id)
                    ->first();

        return view('mahasiswa.modul_show', compact('modul', 'nilai'));
    })->name('modul.show');
    
    // Quiz Submission
    Route::post('/kuis/submit/{modul_id}', function ($modul_id) {
        $modul = Modul::with('mataKuliah')->findOrFail($modul_id);
        if ($modul->mataKuliah->jurusan !== Auth::user()->jurusan) abort(403);
        if ($modul->type !== 'kuis') abort(400);

        Nilai::updateOrCreate(
            ['mahasiswa_id' => Auth::id(), 'modul_id' => $modul_id],
            ['status' => 'submitted']
        );

        return back()->with('success', 'Kuis berhasil disubmit!');
    })->name('kuis.submit');

    // Nilai & Transkrip (Includes IPK & Tugas Submissions)
    Route::get('/nilai', function () {
        $nilais = Nilai::where('mahasiswa_id', Auth::id())
                    ->with(['modul.mataKuliah'])
                    ->get();
        $tugasSubmissions = TugasSubmission::where('mahasiswa_id', Auth::id())
                    ->with(['tugas.mataKuliah'])
                    ->get();
        $user = Auth::user();
        return view('mahasiswa.nilai', compact('nilais', 'tugasSubmissions', 'user'));
    })->name('nilai');

    // Jadwal Perkuliahan Interaktif BEC (Master PDF Sheet)
    Route::get('/jadwal', function () {
        $userAngkatan = Auth::user()->angkatan ?? (Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30);
        $allJadwals = Jadwal::with(['mataKuliah', 'dosen'])->where('angkatan', $userAngkatan)->get();

        // Key map by "hari-kelas-jam" e.g. "Senin-1A-08:00" for 100% precise O(1) lookup
        $jadwalMap = [];
        foreach ($allJadwals as $j) {
            $jam = substr($j->jam_mulai, 0, 5);
            $key = $j->hari . '-' . $j->kelas . '-' . $jam;
            $jadwalMap[$key] = $j;
        }

        $classes = KelasList::where('angkatan', $userAngkatan)->orderBy('name')->pluck('name')->toArray();
        if (empty($classes)) {
            $classes = $allJadwals->pluck('kelas')->unique()->filter()->values()->toArray();
        }

        return view('mahasiswa.jadwal', compact('allJadwals', 'jadwalMap', 'classes', 'userAngkatan'));
    })->name('jadwal');

    // Dedicated Print / Download PDF Route
    Route::get('/jadwal/print', function () {
        $userAngkatan = Auth::user()->angkatan ?? (Angkatan::where('is_active', true)->value('nomor_angkatan') ?? 30);
        $allJadwals = Jadwal::with(['mataKuliah', 'dosen'])->where('angkatan', $userAngkatan)->get();

        $jadwalMap = [];
        foreach ($allJadwals as $j) {
            $jam = substr($j->jam_mulai, 0, 5);
            $key = $j->hari . '-' . $j->kelas . '-' . $jam;
            $jadwalMap[$key] = $j;
        }

        $classes = KelasList::where('angkatan', $userAngkatan)->orderBy('name')->pluck('name')->toArray();
        if (empty($classes)) {
            $classes = $allJadwals->pluck('kelas')->unique()->filter()->values()->toArray();
        }

        return view('mahasiswa.jadwal_print', compact('allJadwals', 'jadwalMap', 'classes', 'userAngkatan'));
    })->name('jadwal.print');

    // Dedicated Page per Kelas (Dynamic from database)
    Route::get('/jadwal/kelas/{kelas}', function ($kelas) {
        $validClasses = KelasList::pluck('name')->toArray();
        if (count($validClasses) === 0) {
            $validClasses = ['1A', '1B', '1C', '1D', '1E', '1F', '1G', '1H', '3A', '3B', '3C', '3D', '3E', '3F', '3G', '3H'];
        }
        $validClasses = array_unique(array_merge($validClasses, Jadwal::distinct()->pluck('kelas')->toArray()));
        if (!in_array($kelas, $validClasses)) abort(404);

        $jadwals = Jadwal::where('kelas', $kelas)->with(['mataKuliah', 'dosen'])->get();
        $targetAngkatan = Jadwal::where('kelas', $kelas)->value('angkatan') 
            ?? KelasList::where('name', $kelas)->value('angkatan') 
            ?? (str_starts_with($kelas, '1') ? 30 : 29);
        $availableClasses = KelasList::where('angkatan', $targetAngkatan)->orderBy('name')->pluck('name')->toArray();
        if (empty($availableClasses)) {
            $availableClasses = Jadwal::where('angkatan', $targetAngkatan)->distinct()->pluck('kelas')->toArray();
        }
        if (empty($availableClasses)) {
            $availableClasses = $targetAngkatan == 30 
                ? ['1A', '1B', '1C', '1D', '1E', '1F', '1G', '1H'] 
                : ['3A', '3B', '3C', '3D', '3E', '3F', '3G', '3H'];
        }

        return view('mahasiswa.jadwal_kelas', compact('jadwals', 'kelas', 'availableClasses', 'targetAngkatan'));
    })->name('jadwal.kelas');

    // Peringkat / Leaderboard (Akademik IPK & BST Typing)
    Route::get('/peringkat', [LeaderboardController::class, 'index'])->name('peringkat');

    // Tugas Praktikum
    Route::get('/tugas', function () {
        $tugases = Tugas::whereHas('mataKuliah', function($q) {
            $q->where('jurusan', Auth::user()->jurusan);
        })->with(['mataKuliah', 'submissions'])->latest()->get();

        return view('mahasiswa.tugas', compact('tugases'));
    })->name('tugas');

    Route::post('/tugas/submit/{tugas_id}', function (Request $request, $tugas_id) {
        $request->validate([
            'file_path' => 'required|file|max:10240',
            'note' => 'nullable|string',
        ]);

        $tugas = Tugas::with('mataKuliah')->findOrFail($tugas_id);
        if ($tugas->mataKuliah->jurusan !== Auth::user()->jurusan) abort(403);

        $path = $request->file('file_path')->store('tugas_submissions', 'public');

        TugasSubmission::updateOrCreate(
            ['tugas_id' => $tugas_id, 'mahasiswa_id' => Auth::id()],
            [
                'file_path' => $path,
                'note' => $request->note,
                'status' => 'submitted'
            ]
        );

        return back()->with('success', 'Tugas berhasil diunggah!');
    })->name('tugas.submit');

    // Pengumuman Mahasiswa
    Route::get('/pengumuman', function () {
        $userJurusan = Auth::user()->jurusan;
        $pengumumans = Pengumuman::whereIn('target', ['semua', $userJurusan])
                        ->with('author')
                        ->orderBy('is_pinned', 'desc')
                        ->latest()
                        ->get();

        return view('mahasiswa.pengumuman', compact('pengumumans'));
    })->name('pengumuman');

    // Blind System Typing Practice & Save Score
    Route::get('/typing', function () {
        $topTypists = \App\Models\BstScore::with('mahasiswa')
            ->selectRaw('mahasiswa_id, MAX(cpm) as max_cpm, MAX(wpm) as max_wpm, MAX(accuracy) as max_accuracy')
            ->groupBy('mahasiswa_id')
            ->orderByDesc('max_cpm')
            ->take(5)
            ->get();
        return view('mahasiswa.typing', compact('topTypists'));
    })->name('typing');

    Route::post('/typing/save', [LeaderboardController::class, 'saveBstScore'])->name('typing.save');

    // PDF KHS & BST Certificate Routes
    Route::get('/khs/download', [PdfExportController::class, 'downloadKhs'])->name('khs.download');
    Route::get('/typing/certificate', [PdfExportController::class, 'downloadBstCertificate'])->name('typing.certificate');

    // Modul Progress Tracking Toggle
    Route::post('/modul/{id}/toggle-progress', function ($id) {
        $modul = Modul::findOrFail($id);
        $progress = ModulProgress::firstOrCreate(
            ['mahasiswa_id' => Auth::id(), 'modul_id' => $id]
        );

        $progress->update([
            'is_completed' => !$progress->is_completed,
            'completed_at' => !$progress->is_completed ? now() : null,
        ]);

        return back()->with('success', $progress->is_completed ? 'Modul berhasil ditandai selesai!' : 'Status modul diperbarui!');
    })->name('modul.toggle_progress');

    // 🎯 Simulator & Target IPK
    Route::get('/simulator-ipk', function () {
        $mataKuliahs = MataKuliah::where('jurusan', Auth::user()->jurusan)
            ->where('cawu', Auth::user()->cawu ?? 3)
            ->get();
        return view('mahasiswa.simulator_ipk', compact('mataKuliahs'));
    })->name('simulator_ipk');

    // 🗓️ Agenda Kuliah & To-Do List Mahasiswa
    Route::get('/agenda', function () {
        $tugases = Tugas::whereHas('mataKuliah', function($q) {
            $q->where('jurusan', Auth::user()->jurusan);
        })->with(['mataKuliah', 'submissions' => function($q) {
            $q->where('mahasiswa_id', Auth::id());
        }])->orderBy('deadline')->get();

        $jadwals = Jadwal::where('kelas', Auth::user()->kelas ?? '3A')
            ->with('mataKuliah')
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 ELSE 6 END")
            ->orderBy('jam_mulai')
            ->get();

        return view('mahasiswa.agenda', compact('tugases', 'jadwals'));
    })->name('agenda');

    // ⏱️ Ruang Belajar Fokus (Pomodoro Study Timer)
    Route::get('/focus', function () {
        $moduls = Modul::whereHas('mataKuliah', function($q) {
            $q->where('jurusan', Auth::user()->jurusan);
        })->with('mataKuliah')->latest()->get();
        return view('mahasiswa.focus', compact('moduls'));
    })->name('focus');
});


// ==========================================
// 🔔 REALTIME API NOTIFICATIONS & UPDATES
// ==========================================
Route::middleware('auth')->get('/api/notifications/poll', function (Request $request) {
    $user = Auth::user();
    $since = $request->query('since') ? \Carbon\Carbon::parse($request->query('since')) : now()->subSeconds(30);

    $notifications = [];

    if ($user->role === 'mahasiswa') {
        // 1. Pengumuman baru dari Admin / Dosen
        $pengumumans = Pengumuman::whereIn('target', ['semua', $user->jurusan])
            ->where('created_at', '>', $since)
            ->with('author')
            ->get();

        foreach ($pengumumans as $p) {
            $notifications[] = [
                'id' => 'p-' . $p->id,
                'type' => 'pengumuman',
                'title' => '📢 Pengumuman Baru',
                'message' => $p->title,
                'url' => route('mahasiswa.pengumuman'),
                'created_at' => $p->created_at->toIso8601String(),
            ];
        }

        // 2. Tugas Praktikum Baru dari Dosen
        $tugases = Tugas::whereHas('mataKuliah', function($q) use ($user) {
            $q->where('jurusan', $user->jurusan);
        })->where('created_at', '>', $since)->with('mataKuliah')->get();

        foreach ($tugases as $t) {
            $notifications[] = [
                'id' => 't-' . $t->id,
                'type' => 'tugas',
                'title' => '📝 Tugas Praktikum Baru: ' . ($t->mataKuliah->kode_mk ?? ''),
                'message' => $t->title,
                'url' => route('mahasiswa.tugas'),
                'created_at' => $t->created_at->toIso8601String(),
            ];
        }

        // 3. Modul / Materi Baru dari Dosen
        $moduls = Modul::whereHas('mataKuliah', function($q) use ($user) {
            $q->where('jurusan', $user->jurusan);
        })->where('created_at', '>', $since)->with('mataKuliah')->get();

        foreach ($moduls as $m) {
            $notifications[] = [
                'id' => 'm-' . $m->id,
                'type' => 'modul',
                'title' => '📚 ' . ($m->type === 'kuis' ? 'Kuis Evaluasi Baru' : 'Materi Pembelajaran Baru'),
                'message' => $m->title . ' (' . ($m->mataKuliah->kode_mk ?? '') . ')',
                'url' => route('mahasiswa.modul.show', $m->id),
                'created_at' => $m->created_at->toIso8601String(),
            ];
        }

        // 4. Nilai Tugas Dinilai Dosen
        $gradedSubs = TugasSubmission::where('mahasiswa_id', $user->id)
            ->where('status', 'graded')
            ->where('updated_at', '>', $since)
            ->with('tugas.mataKuliah')
            ->get();

        foreach ($gradedSubs as $sub) {
            $notifications[] = [
                'id' => 'sub-' . $sub->id,
                'type' => 'nilai',
                'title' => '📊 Tugas Dinilai Dosen',
                'message' => 'Nilai: ' . $sub->score . '/100 untuk ' . ($sub->tugas->title ?? 'Tugas'),
                'url' => route('mahasiswa.tugas'),
                'created_at' => $sub->updated_at->toIso8601String(),
            ];
        }

        // 5. Nilai Kuis Masuk
        $gradedNilais = Nilai::where('mahasiswa_id', $user->id)
            ->where('status', 'graded')
            ->where('updated_at', '>', $since)
            ->with('modul.mataKuliah')
            ->get();

        foreach ($gradedNilais as $nil) {
            $notifications[] = [
                'id' => 'nil-' . $nil->id,
                'type' => 'nilai',
                'title' => '🎯 Nilai Kuis Masuk',
                'message' => 'Nilai: ' . $nil->score . '/100 untuk ' . ($nil->modul->title ?? 'Kuis'),
                'url' => route('mahasiswa.nilai'),
                'created_at' => $nil->updated_at->toIso8601String(),
            ];
        }
    }

    return response()->json([
        'success' => true,
        'notifications' => $notifications,
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('api.notifications.poll');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Fallback storage route for environments without symlink support (e.g. InfinityFree shared hosting)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.fallback');

// Impersonate Return Route
Route::get('/impersonate/leave', function () {
    $impersonatorId = session('impersonator_id');
    if ($impersonatorId) {
        $admin = User::find($impersonatorId);
        if ($admin && $admin->role === 'admin') {
            session()->forget('impersonator_id');
            Auth::login($admin);
            return redirect()->route('admin.users', ['role' => 'mahasiswa'])->with('success', 'Kembali ke sesi Administrator BEC.');
        }
    }
    return redirect('/');
})->name('impersonate.leave');

require __DIR__.'/auth.php';
