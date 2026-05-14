<?php
namespace App\Services;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generate(string $bookingId): string
    {
        $url      = route('publik.konfirmasi', $bookingId);
        $filename = 'qr/qr-' . $bookingId . '.svg';
        $path     = storage_path('app/public/' . $filename);

        // Pastikan folder ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        QrCode::format('svg')->size(300)
              ->errorCorrection('H')
              ->generate($url, $path);

        return $filename;
    }
}