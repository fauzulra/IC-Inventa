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
            // Menambahkan kolom keterangan. 
            // Kita beri .nullable() karena sifatnya opsional (tidak wajib diisi)
            // Kita letakkan .after('request_date') agar posisinya rapi di database
            $table->string('keterangan')->nullable()->after('request_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('keterangan');
        });
    }
};