<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Modul;
use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\Pengumuman;
use App\Models\Jadwal;

// Helper to safely format YouTube embed links (handles watch?v=, youtu.be/, live/, &t= params)
function formatYoutubeEmbed($url) {
    if (!$url) return null;
    
    if (str_contains($url, 'youtu.be/')) {
        $id = explode('youtu.be/', $url)[1] ?? '';
        $id = strtok($id, '?');
        return "https://www.youtube.com/embed/" . $id;
    }
    
    if (str_contains($url, 'watch?v=')) {
        $id = explode('watch?v=', $url)[1] ?? '';
        $id = strtok($id, '&');
        return "https://www.youtube.com/embed/" . $id;
    }

    if (str_contains($url, 'embed/')) {
        return $url;
    }

    return $url;
}

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
    
    Route::get('/users/{role}', function ($role) {
        if (!in_array($role, ['mahasiswa', 'dosen'])) abort(404);
        $users = User::where('role', $role)->get();
        return view('admin.users', compact('users', 'role'));
    })->name('users');
    
    Route::delete('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus sesama admin.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    })->name('users.destroy');

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

        // Security check: ensure mata_kuliah_id belongs to auth dosen
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

    // Grading Kuis
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
});


// ==========================================
// 🔵 MAHASISWA ROUTES (Guarded by role:mahasiswa)
// ==========================================
Route::middleware(['auth', 'verified', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard/ap', function () { 
        if (Auth::user()->jurusan !== 'Administrasi Perkantoran') {
            return redirect()->route('mahasiswa.dashboard_bm');
        }
        $mataKuliahs = MataKuliah::where('jurusan', 'Administrasi Perkantoran')
                        ->with(['dosen', 'moduls', 'tugases'])
                        ->withCount(['moduls', 'tugases'])
                        ->get();
        return view('mahasiswa.dashboard_ap', compact('mataKuliahs')); 
    })->name('dashboard_ap');

    Route::get('/dashboard/bm', function () { 
        if (Auth::user()->jurusan !== 'Bisnis Manajemen') {
            return redirect()->route('mahasiswa.dashboard_ap');
        }
        $mataKuliahs = MataKuliah::where('jurusan', 'Bisnis Manajemen')
                        ->with(['dosen', 'moduls', 'tugases'])
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

    // Nilai & Transkrip
    Route::get('/nilai', function () {
        $nilais = Nilai::where('mahasiswa_id', Auth::id())
                    ->with(['modul.mataKuliah'])
                    ->get();
        return view('mahasiswa.nilai', compact('nilais'));
    })->name('nilai');

    // Jadwal Perkuliahan
    Route::get('/jadwal', function () {
        $jadwals = Jadwal::whereHas('mataKuliah', function($q) {
            $q->where('jurusan', Auth::user()->jurusan);
        })->with('mataKuliah.dosen')->get();

        return view('mahasiswa.jadwal', compact('jadwals'));
    })->name('jadwal');

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

    // Blind System Typing Practice (Monkeytype style for BEC)
    Route::get('/typing', function () {
        return view('mahasiswa.typing');
    })->name('typing');
});


// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
