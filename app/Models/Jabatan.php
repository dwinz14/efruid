<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    // ── Level konstanta — gunakan ini, jangan magic number ──────────────
    const LEVEL_NON_HIERARKI = 0;
    const LEVEL_DIRUT        = 1;
    const LEVEL_DIREKTUR     = 2;
    const LEVEL_KABAG        = 3; // Kepala Bagian / Pimpinan Cabang
    const LEVEL_KASIE        = 4; // Kasie / Kepala Unit
    const LEVEL_STAFF        = 5; // Staff & Pelaksana (default)

    /**
     * Label deskriptif untuk tiap level — dipakai di UI admin.
     */
    const LEVEL_LABELS = [
        self::LEVEL_NON_HIERARKI => '0 — Non-Hierarki (Sistem)',
        self::LEVEL_DIRUT        => '1 — Direktur Utama',
        self::LEVEL_DIREKTUR     => '2 — Direktur',
        self::LEVEL_KABAG        => '3 — Kepala Bagian / Pimpinan Cabang',
        self::LEVEL_KASIE        => '4 — Kasie / Kepala Unit',
        self::LEVEL_STAFF        => '5 — Staff & Pelaksana',
    ];

    protected $fillable = ['nama', 'urutan', 'level', 'is_lainnya', 'is_active'];

    protected $casts = [
        'level'      => 'integer',
        'urutan'     => 'integer',
        'is_lainnya' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ── Relationships ──

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ── Scopes ──

    public static function aktif()
    {
        return static::where('is_active', true)
            ->orderBy('level')
            ->orderBy('urutan')
            ->orderBy('nama');
    }

    // ── Business logic ────────────────────────────────────────────────────

    /**
     * Resolve level jabatan atasan yang dibutuhkan oleh pemohon dengan level ini.
     *
     * Aturan:
     *   L5 (Staff)   → butuh atasan L4 (Kasie)
     *   L4 (Kasie)   → butuh atasan L3 (KaBag/Pimcab)
     *   L3 (KaBag)   → butuh atasan L1 (Dirut)
     *   L2 (Direktur)→ butuh atasan L1 (Dirut)
     *   L1 (Dirut)   → tidak butuh atasan (null)
     *   L0           → fallback ke L5 behavior
     *
     * @return int|null  Level target atasan, null jika tidak perlu atasan
     */
    public function resolveTargetAtasanLevels(): array
    {
        return match ($this->level) {
            self::LEVEL_DIRUT    => [],
            self::LEVEL_DIREKTUR => [self::LEVEL_DIRUT],
            self::LEVEL_KABAG    => [self::LEVEL_DIRUT],
            self::LEVEL_KASIE    => [self::LEVEL_KABAG],
            default              => [
                self::LEVEL_KASIE,
                self::LEVEL_KABAG,
            ],
        };
    }

    /**
     * Apakah jabatan ini perlu atasan dalam proses permohonan?
     */
    public function needsAtasan(): bool
    {
        return $this->resolveTargetAtasanLevels() !== null;
    }
}
