<?php

use App\Models\DataPemilik;
use App\Models\ReservasiHotel;
use App\Models\ReservasiLayanan;
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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataPemilik::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ReservasiHotel::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ReservasiLayanan::class)->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal_pembayaran');
            $table->integer('Subtotal')->nullable();
            $table->enum('status_pemabayaran', ['Transfer', 'Cash']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
