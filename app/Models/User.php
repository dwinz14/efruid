<?php

namespace App\Models;

use App\Enums\RoleUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'kantor_id',
        'jabatan_id',
        'jabatan_custom',
        'signature_path',
        'is_active',
        'email_verified',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password'       => 'hashed',
        'is_active'      => 'boolean',
        'email_verified' => 'boolean',
        'last_login_at'  => 'datetime',
    ];

    // ── Relationships ──

    public function kantor(): BelongsTo
    {
        return $this->belongsTo(Kantor::class);
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withPivot('assigned_at');
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'pemohon_id');
    }

    // ── Role helpers ──

    public function hasRole(RoleUser|string $role): bool
    {
        $name = $role instanceof RoleUser ? $role->value : $role;
        return $this->roles->contains('name', $name);
    }

    public function hasAnyRole(array $roles): bool
    {
        $names = array_map(fn($r) => $r instanceof RoleUser ? $r->value : $r, $roles);
        return $this->roles->whereIn('name', $names)->isNotEmpty();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleUser::SUPER_ADMIN);
    }
    public function isAtasan(): bool
    {
        return $this->hasRole(RoleUser::ATASAN);
    }
    public function isDirut(): bool
    {
        return $this->hasRole(RoleUser::DIRUT);
    }
    public function isItStaff(): bool
    {
        return $this->hasRole(RoleUser::IT_STAFF);
    }
    public function isPemohon(): bool
    {
        return $this->hasRole(RoleUser::PEMOHON);
    }

    // ── Attribute helpers ──

    /** Nama jabatan yang ditampilkan (custom jika is_lainnya) */
    public function getJabatanLabelAttribute(): string
    {
        if ($this->jabatan?->is_lainnya && $this->jabatan_custom) {
            return $this->jabatan_custom;
        }
        return $this->jabatan?->nama ?? '';
    }

    /** Signature URL untuk ditampilkan di view (via route controller) */
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_path
            ? route('signature.show', ['user' => $this->id])
            : null;
    }
}
