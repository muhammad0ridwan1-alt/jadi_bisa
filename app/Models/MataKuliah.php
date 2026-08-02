<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $fillable = ['name', 'kode_mk', 'jurusan', 'dosen_id'];

    public function moduls() {
        return $this->hasMany(Modul::class);
    }

    public function dosen() {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function tugases() {
        return $this->hasMany(Tugas::class);
    }

    public function absensis() {
        return $this->hasMany(Absensi::class);
    }

    public function jadwals() {
        return $this->hasMany(Jadwal::class);
    }
}
