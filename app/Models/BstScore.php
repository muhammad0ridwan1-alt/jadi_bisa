<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BstScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'cpm',
        'wpm',
        'accuracy',
        'cawu',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
