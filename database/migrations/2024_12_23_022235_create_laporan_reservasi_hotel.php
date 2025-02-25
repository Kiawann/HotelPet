<?php

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
        Schema::create('laporan_reservasi_hotel', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Room::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal_checkin');
            $table->date('tanggal_checkout');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_reservasi_hotel');
    }
};
