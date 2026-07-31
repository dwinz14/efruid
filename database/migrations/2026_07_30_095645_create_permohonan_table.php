<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen', 50)->nullable()->unique()
                ->comment('Format: FRUID/{KODE}/{TAHUN}/{SEQ} — di-generate saat submit');

            // Metadata form
            $table->string('form_type', 20)->comment('normal | rangkap');
            $table->date('tanggal_permohonan')->comment('Di-set otomatis dari server, tidak dari input user');

            // Data pemohon (snapshot saat submit)
            $table->foreignId('pemohon_id')->constrained('users');
            $table->foreignId('kantor_id')->constrained('kantors');
            $table->string('nama_pemohon', 150);
            $table->string('jabatan_pemohon', 150)->comment('Snapshot nama jabatan saat submit');
            $table->string('nik_pemohon', 20);
            $table->string('user_id_ussi', 30)->comment('User ID sistem USSI');

            // Jenis permohonan
            $table->string('jenis_permohonan', 20)->comment('pendaftaran | perubahan | nonaktif');
            $table->string('tipe_perubahan', 20)->nullable()->comment('permanen | sementara');
            $table->string('jabatan_lama', 150)->nullable();
            $table->string('jabatan_baru', 150)->nullable();
            $table->string('alasan_perubahan', 255)->nullable();
            $table->date('tgl_permanen')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->date('tgl_nonaktif')->nullable();
            $table->string('access_level', 20)->comment('DIREKSI | ADMINISTRATOR | USER');

            // Status & approval chain
            $table->string('status', 30)->default('DRAFT');
            $table->foreignId('atasan_id')->nullable()->constrained('users')->nullOnDelete()
                ->comment('User yang dipilih sebagai atasan saat submit');
            $table->string('nama_atasan_ttd', 150)->nullable()
                ->comment('Snapshot nama atasan saat submit');

            // Tanda tangan & stamp (path ke storage)
            $table->string('ttd_pemohon_path')->nullable();
            $table->string('ttd_atasan_path')->nullable();
            $table->string('ttd_dirut_path')->nullable();
            $table->json('verification_stamps')->nullable()
                ->comment('Array: [{role, nama, jabatan, timestamp, hash}]');

            // PDF final
            $table->string('pdf_path')->nullable();

            // Revisi
            $table->unsignedSmallInteger('revision_count')->default(0);
            $table->text('alasan_reject')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk query umum
            $table->index(['pemohon_id', 'status']);
            $table->index(['kantor_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan');
    }
};
