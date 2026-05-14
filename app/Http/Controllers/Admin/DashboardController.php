<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Models\Denda;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats untuk card dashboard
        $totalBuku        = Buku::count();
        $totalMahasiswa   = Mahasiswa::count();
        $peminjamanAktif  = Peminjaman::where('status', 'dipinjam')->count();
        $pendingRequest   = Peminjaman::where('status', 'pending')->count();
        $dendaBelumBayar  = Denda::where('status_bayar', 'belum_bayar')->sum('total_denda');

        // Data tabel terbaru
        $peminjamanTerbaru = Peminjaman::with(['mahasiswa', 'buku'])
                                        ->latest()->take(5)->get();

        // Buku stok kritis (stok tersedia < 3)
        $stokKritis = Buku::where('stok_tersedia', '<', 3)->get();

        return view('admin.dashboard.index', compact(
            'totalBuku',
            'totalMahasiswa',
            'peminjamanAktif',
            'pendingRequest',
            'dendaBelumBayar',
            'peminjamanTerbaru',
            'stokKritis'
        ));
    }
}