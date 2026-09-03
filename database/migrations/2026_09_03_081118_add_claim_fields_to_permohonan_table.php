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
        Schema::table('permohonan', function (Blueprint $table) {
            $table->foreignId('executor_id')
                ->nullable()
                ->after('atasan_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('claimed_at')
                ->nullable()
                ->after('executor_id');

            $table->string('nama_executor')
                ->nullable()
                ->after('claimed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan', function (Blueprint $table) {
            $table->dropForeign(['executor_id']);
            $table->dropColumn(['executor_id', 'claimed_at', 'nama_executor']);
        });
    }
};
