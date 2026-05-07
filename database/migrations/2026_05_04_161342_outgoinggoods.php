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
        Schema::create('outgoing_goods', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel Master Material
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            
            // LOGIKA BARU: Proyek Asal dan Proyek Tujuan
            // Kita arahkan (constrained) secara eksplisit ke tabel 'projects'
            $table->foreignId('source_project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('destination_project_id')->constrained('projects')->cascadeOnDelete();
            
            // Detail Pengeluaran
            $table->integer('quantity');
            $table->date('date_shipped');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_goods');
    }
};