<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugases';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'title',
        'description',
        'file_path',
        'deadline',
        'max_score',
        'angkatan',
        'tahun_ajaran',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function submissions()
    {
        return $this->hasMany(TugasSubmission::class, 'tugas_id');
    }
}
