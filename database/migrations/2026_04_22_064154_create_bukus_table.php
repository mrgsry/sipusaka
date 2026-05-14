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
    Schema::create('bukus', function (Blueprint $table) {
        $table->id();
        $table->string('nama_buku', 200);
        $table->string('penerbit', 100);
        $table->string('jenis_buku', 50);
        $table->string('sampul_buku')->nullable();
        $table->integer('stok_total')->default(0);
        $table->integer('stok_tersedia')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
