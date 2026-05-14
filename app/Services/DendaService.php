<?php
namespace App\Services;

use App\Models\Peminjaman;
use Carbon\Carbon;

class DendaService
{
    const DENDA_PER_HARI = 10000;

    /** Hitung denda berdasarkan hari keterlambatan */
    public function hitungDenda(Peminjaman $peminjaman): array
    {
        $batas  = Carbon::parse($peminjaman->tanggal_kembali_rencana)->startOfDay();
        $aktual = Carbon::parse($peminjaman->tanggal_kembali_aktual ?? now())->startOfDay();

        // Positif = terlambat, negatif/0 = tepat waktu
        $hariTerlambat = (int) $batas->diffInDays($aktual, false);
        $hariTerlambat = max(0, $hariTerlambat); // pastikan tidak negatif

        $totalDenda = $hariTerlambat * self::DENDA_PER_HARI;

        return [
            'hari_terlambat' => $hariTerlambat,
            'total_denda'    => $totalDenda,
            'terlambat'      => $hariTerlambat > 0,
        ];
    }

    /** Generate Booking ID unik */
    public function generateBookingId(): string
    {
        $chars     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomStr = '';
        for ($i = 0; $i < 8; $i++) {
            $randomStr .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return 'PJM-' . $randomStr;
    }
}