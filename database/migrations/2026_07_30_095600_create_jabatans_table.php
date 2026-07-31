<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->unsignedSmallInteger('urutan')->default(0)->comment('Urutan tampil di dropdown');
            $table->boolean('is_lainnya')->default(false)->comment('Jika true, tampilkan input teks bebas');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
