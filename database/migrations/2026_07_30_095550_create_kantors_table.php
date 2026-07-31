<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kantors', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            // Fleksibel: bisa huruf, angka, atau kombinasi. Misal: PST, KDR, 001, A01
            $table->string('kode', 10)->unique()->comment('Kode singkat kantor untuk nomor dokumen');
            $table->boolean('is_pusat')->default(false)->comment('True jika kantor pusat');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kantors');
    }
};
