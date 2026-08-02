<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['mata_kuliah_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
