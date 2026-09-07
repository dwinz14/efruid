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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('suspended_at')
                ->nullable()
                ->after('last_login_ip')
                ->comment('Null = tidak suspended. Diisi = account suspended oleh admin.');

            $table->string('suspension_reason', 255)
                ->nullable()
                ->after('suspended_at')
                ->comment('Alasan suspend yang diisi oleh Super Admin.');

            $table->unsignedTinyInteger('failed_login_count')
                ->default(0)
                ->after('suspension_reason')
                ->comment('Counter gagal login. Di-reset saat login berhasil.');

            $table->timestamp('locked_at')
                ->nullable()
                ->after('failed_login_count')
                ->comment('Null = tidak terkunci. Diisi = account dikunci manual oleh admin.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'suspended_at',
                'suspension_reason',
                'failed_login_count',
                'locked_at',
            ]);
        });
    }
};
