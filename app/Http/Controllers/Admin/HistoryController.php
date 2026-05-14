<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function index()
    {
        $histories = History::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])
        ->latest()
        ->paginate(50);

        return view('admin.history.index', compact('histories'));
    }

    public function exportPdf()
    {
        $histories = History::with(['peminjaman' => function($query) {
            $query->with(['mahasiswa', 'buku']);
        }])
        ->latest()
        ->get();

        $pdf = Pdf::loadView('admin.history.pdf', compact('histories'));
        return $pdf->download('laporan-history-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new \App\Exports\HistoryExport(), 'laporan-history-' . date('Y-m-d') . '.xlsx');
    }
}
