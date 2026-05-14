<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'pinjaman_id',
        'aksi',
        'keterangan',
        'dilakukan_oleh',
    ];

    public function peminjaman() {
        return $this->belongsTo(Peminjaman::class, 'pinjaman_id');
    }
}