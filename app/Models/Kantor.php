<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kantor extends Model
{
    protected $fillable = ['nama', 'kode', 'is_pusat', 'is_active'];

    protected $casts = [
        'is_pusat'  => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class);
    }

    /** Label tampil di dropdown: "PUSAT" atau "CABANG BLITAR" */
    public function getLabelAttribute(): string
    {
        return $this->is_pusat ? $this->nama : 'CABANG ' . $this->nama;
    }
}
