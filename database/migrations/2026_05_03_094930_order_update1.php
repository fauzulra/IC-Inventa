<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->foreignId('project_id')->constrained(); 
            $table->integer('quantity');
            
            // REVISI 1: Ubah integer menjadi string
            $table->string('unit'); 
            
            $table->date('request_date');
            
            // REVISI 2: Jadikan string agar tidak error jika UI mengirim teks bahasa Indonesia
            $table->string('status')->default('pending'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};