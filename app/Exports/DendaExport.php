<?php

namespace App\Exports;

use App\Models\Denda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DendaExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Denda::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])->get()->map(function ($d) {
            return [
                'Booking ID'       => $d->peminjaman->booking_id ?? '-',
                'Nama Mahasiswa'   => $d->peminjaman->mahasiswa->nama ?? '-',
                'NIM'              => $d->peminjaman->mahasiswa->nim ?? '-',
                'Nama Buku'        => $d->peminjaman->buku->nama_buku ?? '-',
                'Hari Terlambat'   => $d->hari_terlambat,
                'Total Denda'      => 'Rp ' . number_format($d->total_denda, 0, ',', '.'),
                'Status Bayar'     => $d->status_bayar,
                'Dibayar Tanggal'  => $d->dibayar_at ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Booking ID', 'Nama Mahasiswa', 'NIM', 'Nama Buku', 'Hari Terlambat', 'Total Denda', 'Status Bayar', 'Dibayar Tanggal'];
    }

    public function styles(Worksheet $sheet)
    {
        return [109230640056
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'fgColor' => ['rgb' => '4472C4']], 'font' => ['color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
