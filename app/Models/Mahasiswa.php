<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'jurusan',
        'no_telepon',
        'angkatan',
        'email',
        'status',
        'referral_token',
    ];

    public function pinjamans() {
        return $this->hasMany(Peminjaman::class);
    }
}