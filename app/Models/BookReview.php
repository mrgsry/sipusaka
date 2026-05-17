<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    protected $fillable = [
        'buku_id',
        'mahasiswa_id',
        'rating',
        'comment',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}