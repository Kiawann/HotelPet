<?php

use App\Models\DataPemilik;
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
        Schema::create('reservasi_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataPemilik::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal_reservasi');
            $table->enum('status',['Booked','Payment','Done',])->default('Booked'); //Add in UserTable before timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_layanan');
    }
};
