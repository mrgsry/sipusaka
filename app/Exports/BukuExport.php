<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Buku;

class BukuExport implements FromCollection, WithHeadings
{
    public function collection() {
        return Buku::select('nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')->get();
    }

    public function headings(): array {
        return ['Nama Buku', 'Penerbit', 'Jenis', 'Stok Total', 'Stok Tersedia'];
    }
}