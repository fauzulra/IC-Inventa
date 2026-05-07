<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_project', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel material
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            // Relasi ke tabel project
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            
            // DISINILAH STOK PER PROYEK DISIMPAN
            $table->integer('stock')->default(0); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_project');
    }
};