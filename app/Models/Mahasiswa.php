<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';

    protected $fillable = [
        'nama',
        'nim',
        'jurusan',
        'no_telepon',
    ];

    public function pinjamans() {
        return $this->hasMany(Peminjaman::class);
    }
}