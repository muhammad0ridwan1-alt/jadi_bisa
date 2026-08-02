<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $fillable = ['dosen_id', 'mata_kuliah_id', 'title', 'description', 'type', 'file_path', 'youtube_link'];

    public function dosen() {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class);
    }

    public function nilais() {
        return $this->hasMany(Nilai::class);
    }

    public function progresses() {
        return $this->hasMany(ModulProgress::class);
    }
}
