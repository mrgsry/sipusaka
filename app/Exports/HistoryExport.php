<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HistoryExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return History::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])->get()->map(function ($h) {
            return [
                'Tanggal' => $h->created_at ?? '-',
                'Booking ID' => $h->peminjaman->booking_id ?? '-',
                'Mahasiswa' => $h->peminjaman->mahasiswa->nama ?? '-',
                'NIM' => $h->peminjaman->mahasiswa->nim ?? '-',
                'Buku' => $h->peminjaman->buku->nama_buku ?? '-',
                'Aksi' => $h->aksi,
                'Keterangan' => $h->keterangan ?? '-',
                'Dilakukan Oleh' => $h->dilakukan_oleh ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Booking ID', 'Mahasiswa', 'NIM', 'Buku', 'Aksi', 'Keterangan', 'Dilakukan Oleh'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'fgColor' => ['rgb' => '4472C4']], 'font' => ['color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
