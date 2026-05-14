<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = [
        'nama_buku',
        'penerbit',
        'jenis_buku',
        'sampul_buku',
        'stok_total',
        'stok_tersedia',
    ];

    public function pinjamans() {
        return $this->hasMany(Peminjaman::class);
    }
}