<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\BookReview;
use App\Models\Buku;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim' => 'required|string|max:255',
            'referral_token' => 'required|string|size:6',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Cek apakah NIM dan token valid
        $mahasiswa = Mahasiswa::where('nim', $request->nim)
            ->where('referral_token', $request->referral_token)
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM atau token referral tidak valid. Pastikan data yang Anda masukkan benar.'
            ], 401);
        }

        // Cek apakah mahasiswa sudah pernah memberikan review untuk buku ini
        $existingReview = BookReview::where('buku_id', $request->buku_id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah memberikan review untuk buku ini.'
            ], 400);
        }

        // Simpan review
        $review = BookReview::create([
            'buku_id' => $request->buku_id,
            'mahasiswa_id' => $mahasiswa->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan!',
            'review' => $review
        ]);
    }

    public function getReviews($bukuId)
    {
        $reviews = BookReview::where('buku_id', $bukuId)
            ->with('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung rata-rata rating
        $averageRating = $reviews->avg('rating') ?: 0;
        $ratingCount = $reviews->count();

        // Hitung distribusi rating (1-5 bintang)
        $ratingCounts = [
            1 => $reviews->where('rating', 1)->count(),
            2 => $reviews->where('rating', 2)->count(),
            3 => $reviews->where('rating', 3)->count(),
            4 => $reviews->where('rating', 4)->count(),
            5 => $reviews->where('rating', 5)->count(),
        ];

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'rating_count' => $ratingCount,
            'rating_counts' => $ratingCounts
        ]);
    }
}