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
        Schema::table('orders', function (Blueprint $table) {
            // 1. Ubah tipe data menjadi string terlebih dahulu (jika sebelumnya INT)
            $table->string('stock', 50)->change();
            
            // 2. Ubah nama kolom dari 'stock' menjadi 'unit'
            $table->renameColumn('stock', 'unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan nama kolom saat di-rollback
            $table->renameColumn('unit', 'stock');
        });
    }
};