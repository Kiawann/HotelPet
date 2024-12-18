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
        Schema::create('rincian_reservasi_hotel', function (Blueprint $table) {
            $table->foreignIdFor(ReservasiHotel::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DataHewan::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Room::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal_checkin')->nullable(); // Kolom untuk tanggal check-in
            $table->date('tanggal_checkout')->nullable(); // Kolom untuk tanggal check-out
            $table->integer('SubTotal')->nullable();
            $table->timestamps();
        });
    }       
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rincian_reservasi_hotel');
    }
};
