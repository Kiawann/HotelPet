<?php

use App\Models\ReservasiLayanan;
use App\Models\KategoriLayanan;
use App\Models\DataHewan;
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
        Schema::create('rincian_reservasi_layanan', function (Blueprint $table) {
            $table->foreignIdFor(ReservasiLayanan::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DataHewan::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(KategoriLayanan::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal_pelayanan'); // Kolom untuk tanggal check-in
            $table->integer('Harga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_reservasi_layanan');
    }
};
