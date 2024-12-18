<?php

use App\Models\DataHewan;
use App\Models\ReservasiHotel;
use App\Models\Room;
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
        Schema::create('laporan_hewan', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ReservasiHotel::class, 'reservasi_hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DataHewan::class, 'data_hewan_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Room::class, 'room_id')->constrained()->cascadeOnDelete();            
            $table->string('Makan');
            $table->string('Minum');
            $table->string('Bab');
            $table->string('Bak');
            $table->string('keterangan');
            $table->date('tanggal_laporan'); // Kolom untuk tanggal check-out
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_hewan');
    }
};
