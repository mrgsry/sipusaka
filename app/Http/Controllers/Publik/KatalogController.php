<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class KatalogController extends Controller
{
    public function index()
    {
        $bukus = Buku::where('stok_tersedia', '>', 0)
                     ->orWhere('stok_tersedia', '=', 0)
                     ->latest()
                     ->get();

        $jenisBuku = Buku::select('jenis_buku')->distinct()->pluck('jenis_buku');

        return view('publik.katalog', compact('bukus', 'jenisBuku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return response()->json($buku);
    }
}