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
    Schema::create('dendas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pinjaman_id')->constrained('pinjamans');
        $table->integer('hari_terlambat');
        $table->decimal('total_denda', 10, 2);
        $table->enum('status_bayar', ['belum_bayar', 'lunas'])->default('belum_bayar');
        $table->timestamp('dibayar_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dendas');
    }
};
