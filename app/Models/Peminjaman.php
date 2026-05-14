<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'pinjamans';

    protected $fillable = [
        'mahasiswa_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'booking_id',
        'approved_at',
        'approved_by',   // ← tambah
         'qr_code_path',  // ← tambah
    ];

    protected $dates = [
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'approved_at',
    ];

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function buku() {
        return $this->belongsTo(Buku::class);
    }

    public function denda() {
    return $this->hasOne(Denda::class, 'pinjaman_id');
}
}
