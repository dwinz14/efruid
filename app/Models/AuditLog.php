<?php

namespace App\Models;

use App\Enums\AksiAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'subject_type',
        'subject_id',
        'nomor_dokumen',
        'before',
        'after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'aksi'       => AksiAudit::class,
        'before'     => 'array',
        'after'      => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
