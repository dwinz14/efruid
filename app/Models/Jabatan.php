<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $fillable = ['nama', 'urutan', 'is_lainnya', 'is_active'];

    protected $casts = [
        'is_lainnya' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function aktif()
    {
        return static::where('is_active', true)->orderBy('urutan')->orderBy('nama');
    }
}
