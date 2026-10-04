<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasList extends Model
{
    use HasFactory;

    protected $table = 'kelas_list';

    protected $fillable = [
        'name',
        'jurusan',
        'angkatan',
    ];

    public function angkatanObj()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan', 'nomor_angkatan');
    }
}
