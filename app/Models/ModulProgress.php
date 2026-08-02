<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulProgress extends Model
{
    protected $table = 'modul_progresses';

    protected $fillable = ['mahasiswa_id', 'modul_id', 'completed'];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }
}
