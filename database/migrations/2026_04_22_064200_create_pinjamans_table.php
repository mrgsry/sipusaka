<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('pinjamans', function (Blueprint $table) {
        $table->id();
        $table->string('booking_id', 20)->unique();
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
        $table->foreignId('buku_id')->constrained('bukus');
        $table->date('tanggal_pinjam')->nullable();
        $table->date('tanggal_kembali_rencana')->nullable();
        $table->date('tanggal_kembali_aktual')->nullable();
        $table->enum('status', [
            'pending', 'dipinjam', 'dikembalikan', 'terlambat'
        ])->default('pending');
        $table->string('qr_code_path')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamans');
    }
};
