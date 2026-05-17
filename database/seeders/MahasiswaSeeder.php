<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate table
        Mahasiswa::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $mahasiswas = [
            [
                'id' => 1,
                'nama' => 'Muhamad Habib',
                'nim' => '109230640052',
                'jurusan' => 'Teknik Informatika',
                'no_telepon' => '089506450514',
                'email' => 'habibartsgrafik@gmail.com',
                'status' => 'approved',
                'referral_token' => 'ZNH4R5',
                'created_at' => '2026-05-14 16:35:27',
                'updated_at' => '2026-05-17 07:31:37',
            ],
            [
                'id' => 2,
                'nama' => 'Dodi Kurniawan',
                'nim' => '109230640053',
                'jurusan' => 'Sistem Informatika',
                'no_telepon' => '089506450515',
                'email' => null,
                'status' => 'approved',
                'referral_token' => '4NH7MK',
                'created_at' => '2026-05-14 17:05:22',
                'updated_at' => '2026-05-16 11:25:04',
            ],
            [
                'id' => 3,
                'nama' => 'Tegar',
                'nim' => '109230640055',
                'jurusan' => 'Teknik Informatika',
                'no_telepon' => '089506450516',
                'email' => null,
                'status' => 'approved',
                'referral_token' => '8J751G',
                'created_at' => '2026-05-15 10:23:22',
                'updated_at' => '2026-05-16 11:25:04',
            ],
            [
                'id' => 4,
                'nama' => 'Alfi',
                'nim' => '109230640057',
                'jurusan' => 'Teknik Informatika',
                'no_telepon' => '08953928472938',
                'email' => null,
                'status' => 'approved',
                'referral_token' => 'A69C8L',
                'created_at' => '2026-05-16 07:09:52',
                'updated_at' => '2026-05-16 11:25:04',
            ],
            [
                'id' => 8,
                'nama' => 'Fikri Awal Ikrom',
                'nim' => '109230640058',
                'jurusan' => 'Teknik Informatika',
                'no_telepon' => '0822356784324',
                'email' => 'fikriotsuki@gmail.com',
                'status' => 'approved',
                'referral_token' => '1HK3W6',
                'created_at' => '2026-05-16 08:03:13',
                'updated_at' => '2026-05-17 06:29:52',
            ],
            [
                'id' => 13,
                'nama' => 'Lazian Suhanda',
                'nim' => '109230640505',
                'jurusan' => 'Teknik Sipil',
                'no_telepon' => '08995064500543',
                'email' => 'm.habibdesain20@gmail.com',
                'status' => 'approved',
                'referral_token' => 'RCN9TS',
                'created_at' => '2026-05-16 13:39:03',
                'updated_at' => '2026-05-16 14:16:09',
            ],
            [
                'id' => 14,
                'nama' => 'Master Fauza',
                'nim' => '109230640112',
                'jurusan' => 'Teknik Industri',
                'no_telepon' => '08150876342',
                'email' => 'm.habibdesain20@gmail.com',
                'status' => 'approved',
                'referral_token' => '0G4BAL',
                'created_at' => '2026-05-16 14:34:38',
                'updated_at' => '2026-05-17 06:19:01',
            ],
            [
                'id' => 15,
                'nama' => 'Yussi Sanjaya',
                'nim' => '109230640023',
                'jurusan' => 'Biologi',
                'no_telepon' => '089506450054',
                'email' => 'sanjayayussi@gmail.com',
                'status' => 'approved',
                'referral_token' => 'S1GD2Z',
                'created_at' => '2026-05-17 06:03:18',
                'updated_at' => '2026-05-17 07:41:39',
            ],
        ];

        foreach ($mahasiswas as $mahasiswa) {
            Mahasiswa::create($mahasiswa);
        }

        $this->command->info('Mahasiswa seeder completed successfully!');
    }
}