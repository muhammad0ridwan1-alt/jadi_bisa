<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = ['user_id', 'title', 'content', 'target', 'is_pinned'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
