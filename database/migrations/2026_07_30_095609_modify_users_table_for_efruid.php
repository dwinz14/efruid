<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom setelah kolom bawaan Laravel
            $table->string('nik', 20)->nullable()->unique()->after('name')
                ->comment('Format: AP + 9 digit angka');
            $table->foreignId('kantor_id')->nullable()->constrained('kantors')
                ->nullOnDelete()->after('nik');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')
                ->nullOnDelete()->after('kantor_id');
            $table->string('jabatan_custom', 100)->nullable()->after('jabatan_id')
                ->comment('Diisi jika jabatan is_lainnya = true');
            $table->string('signature_path')->nullable()->after('jabatan_custom')
                ->comment('Path relatif ke storage/app/signatures/');
            $table->boolean('is_active')->default(true)->after('signature_path');
            $table->boolean('email_verified')->default(false)->after('is_active')
                ->comment('True setelah OTP diverifikasi');
            $table->timestamp('last_login_at')->nullable()->after('email_verified');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            // Hapus kolom email_verified_at bawaan — kita pakai email_verified boolean
            $table->dropColumn('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kantor_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn([
                'nik',
                'kantor_id',
                'jabatan_id',
                'jabatan_custom',
                'signature_path',
                'is_active',
                'email_verified',
                'last_login_at',
                'last_login_ip',
            ]);
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
