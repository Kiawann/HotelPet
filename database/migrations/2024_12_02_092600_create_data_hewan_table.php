<?php

use App\Models\Admin\KategoriHewan;
use App\Models\DataPemilik;
use App\Models\KategoriHewan as ModelsKategoriHewan;
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
        Schema::create('data_hewan', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DataPemilik::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ModelsKategoriHewan::class)->constrained()->cascadeOnDelete();
            $table->string('nama_hewan');
            $table->integer('umur');
            $table->integer('berat_badan');
            $table->enum('jenis_kelamin', ['Jantan', 'Betina']);
            $table->string('warna');
            $table->string('ras_hewan');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_hewan');
    }
};
