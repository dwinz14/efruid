<?php

namespace App\Models;

use App\Enums\AccessLevel;
use App\Enums\FormType;
use App\Enums\JenisPermohonan;
use App\Enums\StatusPermohonan;
use App\Enums\TipePerubahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permohonan extends Model
{
    use SoftDeletes;

    protected $table = 'permohonan';

    protected $fillable = [
        'nomor_dokumen',
        'form_type',
        'tanggal_permohonan',
        'pemohon_id',
        'kantor_id',
        'nama_pemohon',
        'jabatan_pemohon',
        'nik_pemohon',
        'user_id_ussi',
        'jenis_permohonan',
        'tipe_perubahan',
        'jabatan_lama',
        'jabatan_baru',
        'alasan_perubahan',
        'tgl_permanen',
        'tgl_mulai',
        'tgl_selesai',
        'tgl_nonaktif',
        'access_level',
        'status',
        'atasan_id',
        'nama_atasan_ttd',
        'ttd_pemohon_path',
        'ttd_atasan_path',
        'ttd_dirut_path',
        'ttd_executor_path',
        'verification_stamps',
        'pdf_path',
        'revision_count',
        'alasan_reject',
        'executor_id',
        'claimed_at',
        'nama_executor',
    ];

    protected $casts = [
        'form_type'          => FormType::class,
        'jenis_permohonan'   => JenisPermohonan::class,
        'tipe_perubahan'     => TipePerubahan::class,
        'access_level'       => AccessLevel::class,
        'status'             => StatusPermohonan::class,
        'verification_stamps' => 'array',
        'tanggal_permohonan' => 'date',
        'tgl_permanen'       => 'date',
        'tgl_mulai'          => 'date',
        'tgl_selesai'        => 'date',
        'tgl_nonaktif'       => 'date',
        'claimed_at'         => 'datetime',
    ];

    // ── Relationships ──

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class)->orderBy('created_at');
    }

    // ── Status helpers ──

    public function isDraft(): bool
    {
        return $this->status === StatusPermohonan::DRAFT;
    }
    public function isExecuted(): bool
    {
        return $this->status === StatusPermohonan::EXECUTED;
    }
    public function isCancellable(): bool
    {
        return $this->status->cancellable();
    }
    public function isRevisable(): bool
    {
        return $this->status->revisable();
    }
    public function requiresDirut(): bool
    {
        return $this->form_type->requiresDirut();
    }
    public function isClaimed(): bool
    {
        return $this->executor_id !== null;
    }

    public function isClaimedBy(int $userId): bool
    {
        return $this->executor_id === $userId;
    }
}
