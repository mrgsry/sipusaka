<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DendaController extends Controller
{
    public function index()
    {
        $dendas = Denda::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])
        ->latest()
        ->get();

        return view('admin.denda.index', compact('dendas'));
    }

    public function tandaiLunas($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->update([
            'status_bayar' => 'lunas',
            'dibayar_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Denda berhasil ditandai lunas!'
        ]);
    }

    public function exportPdf()
    {
        $dendas = Denda::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])
        ->latest()
        ->get();

        $pdf = Pdf::loadView('admin.denda.pdf', compact('dendas'));
        return $pdf->download('laporan-denda-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new \App\Exports\DendaExport(), 'laporan-denda-' . date('Y-m-d') . '.xlsx');
    }
}
