<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $table = 'dendas';

    protected $fillable = [
        'pinjaman_id',
        'hari_terlambat',
        'total_denda',
        'status_bayar',
        'dibayar_at',
    ];

    protected $dates = [
        'dibayar_at',
    ];

    public function peminjaman() {
    return $this->belongsTo(Peminjaman::class, 'pinjaman_id');
}
}
