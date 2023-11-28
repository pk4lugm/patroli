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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('user_update')->nullable();
            $table->string('no_laporan');
            $table->string('judul');
            $table->longText('deskripsi')->nullable();
            $table->datetime('tanggal');
            $table->string('phone');
            $table->string('photo')->nullable();
            $table->enum('status_laporan', ['request', 'accept'])->default('request');
            $table->string('lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
