<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class KatalogController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('reviews')
                     ->where('stok_tersedia', '>', 0)
                     ->orWhere('stok_tersedia', '=', 0)
                     ->latest()
                     ->get();

        // Calculate average rating for each book
        $bukus->each(function($buku) {
            $buku->average_rating = $buku->reviews->avg('rating') ?: 0;
            $buku->review_count = $buku->reviews->count();
        });

        $jenisBuku = Buku::select('jenis_buku')->distinct()->pluck('jenis_buku');

        return view('publik.katalog', compact('bukus', 'jenisBuku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        
        // Increment view count
        $buku->increment('view_count');
        
        return view('publik.book-info', compact('buku'));
    }

    public function ebookReader($id)
    {
        $buku = Buku::findOrFail($id);
        
        if (!$buku->file_ebook) {
            abort(404, 'E-book tidak tersedia');
        }

        // Increment view count when opening ebook reader
        $buku->increment('view_count');

        return view('publik.ebook-reader', compact('buku'));
    }

    public function streamPdf($id)
    {
        $buku = Buku::findOrFail($id);
        
        if (!$buku->file_ebook) {
            abort(404);
        }

        $path = storage_path('app/public/' . $buku->file_ebook);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ebook.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store',
        ]);
    }
}
