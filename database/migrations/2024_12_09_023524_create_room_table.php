<?php

use App\Models\CategoryHotel;
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
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CategoryHotel::class)->constrained()->cascadeOnDelete();
            $table->string('nama_ruangan');
            $table->enum('status',['Tersedia','Tidak Tersedia'])->default('Tersedia'); //Add in UserTable before timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room');
    }
};
