<?php

namespace App\Http\Controllers;

use App\Models\BstScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Academic Ranking (Only students with at least 1 graded assignment/quiz)
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with(['tugasSubmissions', 'nilais'])
            ->get()
            ->map(function ($mhs) {
                $mhs->avg_score = $mhs->akumulasi_nilai;
                $mhs->ipk_score = $mhs->ipk;
                return $mhs;
            })
            ->filter(function ($mhs) {
                return $mhs->avg_score > 0;
            })
            ->sortByDesc('avg_score')
            ->values();

        // 2. BST Typing Ranking (Top Scores per Mahasiswa, sorted by CPM desc, accuracy desc)
        $bstRankings = BstScore::with('mahasiswa')
            ->selectRaw('mahasiswa_id, MAX(cpm) as max_cpm, MAX(wpm) as max_wpm, MAX(accuracy) as max_accuracy')
            ->groupBy('mahasiswa_id')
            ->orderByDesc('max_cpm')
            ->orderByDesc('max_accuracy')
            ->take(20)
            ->get();

        return view('mahasiswa.peringkat', compact('mahasiswas', 'bstRankings'));
    }

    public function saveBstScore(Request $request)
    {
        $validated = $request->validate([
            'cpm' => 'required|integer|min:0|max:1200',
            'wpm' => 'required|integer|min:0|max:250',
            'accuracy' => 'required|numeric|min:0|max:100',
        ]);

        $user = Auth::user();

        $score = BstScore::create([
            'mahasiswa_id' => Auth::id(),
            'cpm' => $validated['cpm'],
            'wpm' => $validated['wpm'],
            'accuracy' => $validated['accuracy'],
            'cawu' => $user->cawu ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Skor BST berhasil disimpan!',
            'score' => $score
        ]);
    }
}
