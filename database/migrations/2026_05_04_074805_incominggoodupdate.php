<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incoming_goods', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel Proyek (Barang ini masuk ke proyek mana?)
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke tabel Material (Barang apa yang masuk?)
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke tabel Pemasok (Boleh kosong/nullable jika barang hasil transfer)
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('quantity');
            $table->date('date_received');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incoming_goods');
    }
};