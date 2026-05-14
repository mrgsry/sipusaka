<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Peminjaman::with(['mahasiswa', 'buku'])->get()->map(function ($p) {
            return [
                'Booking ID'      => $p->booking_id,
                'Nama Mahasiswa'  => $p->mahasiswa->nama ?? '-',
                'NIM'             => $p->mahasiswa->nim ?? '-',
                'Nama Buku'       => $p->buku->nama_buku ?? '-',
                'Tgl Pinjam'      => $p->tanggal_pinjam ?? '-',
                'Batas Kembali'   => $p->tanggal_kembali_rencana ?? '-',
                'Status'          => $p->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Booking ID', 'Nama Mahasiswa', 'NIM', 'Nama Buku', 'Tgl Pinjam', 'Batas Kembali', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a3c6b']],
            ],
        ];
    }
}