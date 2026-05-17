<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate table
        Buku::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $bukus = [
            [
                'id' => 9,
                'nama_buku' => 'Belajar HTML Dasar',
                'penerbit' => 'Sindo',
                'jenis_buku' => 'Teknologi',
                'genre_buku' => 'Ebook',
                'sampul_buku' => 'sampul/c7FbtaeCEjj25OHAk6keY1UTh1MaBnIu2yF9S0Iq.jpg',
                'file_ebook' => 'ebooks/46BA32VrEv6Zw54bt7Mxp6thRjwol2o5QEzUHS5y.pdf',
                'view_count' => 13,
                'borrow_count' => 0,
                'stok_total' => 1,
                'stok_tersedia' => 1,
                'created_at' => '2026-05-15 07:45:55',
                'updated_at' => '2026-05-16 14:09:19',
            ],
            [
                'id' => 10,
                'nama_buku' => 'Fisika Atom',
                'penerbit' => 'Sindo',
                'jenis_buku' => 'Akademik',
                'genre_buku' => 'Ebook',
                'sampul_buku' => 'sampul/TeKojkWBeJls29lMPPVNsxXAboOeLOQ5zAZ6vdry.jpg',
                'file_ebook' => 'ebooks/D1ZA6DyTTgonDvx4Hjl8rl7gpkIK1m6V2sEBJPuN.pdf',
                'view_count' => 7,
                'borrow_count' => 0,
                'stok_total' => 1,
                'stok_tersedia' => 1,
                'created_at' => '2026-05-15 08:05:41',
                'updated_at' => '2026-05-17 07:27:35',
            ],
            [
                'id' => 11,
                'nama_buku' => 'Kalkulus',
                'penerbit' => 'Sutarman Borean',
                'jenis_buku' => 'Akademik',
                'genre_buku' => 'Fisik',
                'sampul_buku' => 'sampul/VFNNaIdAHxjHGIlvKdcISlbuT9wv2IJgHUGhxM72.jpg',
                'file_ebook' => null,
                'view_count' => 0,
                'borrow_count' => 5,
                'stok_total' => 11,
                'stok_tersedia' => 7,
                'created_at' => '2026-05-15 10:09:24',
                'updated_at' => '2026-05-17 07:31:51',
            ],
            [
                'id' => 12,
                'nama_buku' => 'Mikrobiologi Dasar',
                'penerbit' => 'Dr HM Subandi, Drs, Ir, MP',
                'jenis_buku' => 'Akademik',
                'genre_buku' => 'Fisik',
                'sampul_buku' => 'sampul/t7X0qBYaAHnuFrlfJQzANacu6rZhh4BFD1c5YKAQ.jpg',
                'file_ebook' => null,
                'view_count' => 0,
                'borrow_count' => 2,
                'stok_total' => 5,
                'stok_tersedia' => 3,
                'created_at' => '2026-05-17 07:18:35',
                'updated_at' => '2026-05-17 07:42:08',
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }

        $this->command->info('Buku seeder completed successfully!');
    }
}