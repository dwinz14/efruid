<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained('permohonan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('aksi', 30)->comment('approved | rejected | revised | executed');
            $table->string('status_dari', 30);
            $table->string('status_ke', 30);
            $table->text('catatan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('permohonan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
    }
};
