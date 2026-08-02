<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['mata_kuliah_id', 'pertemuan_ke', 'topik', 'tanggal'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }
}
