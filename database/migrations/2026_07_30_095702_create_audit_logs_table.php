<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi', 50)->comment('Nilai dari AksiAudit enum');
            $table->string('subject_type', 100)->nullable()->comment('Model class yang terdampak');
            $table->unsignedBigInteger('subject_id')->nullable()->comment('ID record yang terdampak');
            $table->string('nomor_dokumen', 50)->nullable()->comment('Untuk quick filter by doc number');
            $table->json('before')->nullable()->comment('State sebelum perubahan');
            $table->json('after')->nullable()->comment('State setelah perubahan');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 300)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['aksi', 'created_at']);
            $table->index('nomor_dokumen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
