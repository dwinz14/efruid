<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'permohonan_id',
        'user_id',
        'aksi',
        'status_dari',
        'status_ke',
        'catatan',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
