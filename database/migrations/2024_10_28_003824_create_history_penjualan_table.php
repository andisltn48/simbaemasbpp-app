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
        Schema::create('history_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('id_sampah');
            $table->string('id_nasabah');
            $table->string('id_pembelian');
            $table->string('nama_sampah');
            $table->float('jumlah_jual');
            $table->integer('harga');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_penjualan');
    }
};
