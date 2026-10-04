<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jurusan',
        'kelas',
        'angkatan',
        'tahun_ajaran',
        'cawu',
        'nim',
        'semester',
        'phone',
        'address',
        'avatar',
        'kode_dosen',
        'mata_kuliah_diampu',
        'sesi_per_kelas',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bstScores()
    {
        return $this->hasMany(BstScore::class, 'mahasiswa_id');
    }

    public function tugasSubmissions()
    {
        return $this->hasMany(TugasSubmission::class, 'mahasiswa_id');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }

    public function bestBstScore()
    {
        return $this->hasOne(BstScore::class, 'mahasiswa_id')->orderByDesc('cpm');
    }

    // Auto-calculating IPK and Average Score from Submissions & Modul Nilai
    public function getAkumulasiNilaiAttribute()
    {
        $scores = [];
        
        foreach ($this->tugasSubmissions as $sub) {
            if ($sub->score !== null) {
                $scores[] = $sub->score;
            }
        }

        foreach ($this->nilais as $n) {
            if ($n->score !== null) {
                $scores[] = $n->score;
            }
        }

        if (count($scores) === 0) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    public function getIpkAttribute()
    {
        $avg = $this->getAkumulasiNilaiAttribute();
        // Convert scale 0-100 to IPK 0-4.0
        return round(($avg / 100) * 4.0, 2);
    }
}
