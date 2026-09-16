<?php

namespace App\Services;

use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\User;

/** Builds an in-memory FRUID document for the wizard preview. */
class DocumentPreviewFactory
{
    public function make(User $user, array $data): Permohonan
    {
        $user->loadMissing('kantor');
        $atasan = isset($data['atasan_id']) ? User::find($data['atasan_id']) : null;

        $permohonan = new Permohonan([
            ...$data,
            'tanggal_permohonan' => now(),
            'pemohon_id' => $user->id,
            'nama_pemohon' => $user->name,
            'jabatan_pemohon' => $user->jabatan_label,
            'nik_pemohon' => $user->nik,
            'nama_atasan_ttd' => $atasan?->name,
            'status' => StatusPermohonan::DRAFT,
        ]);

        $permohonan->setRelation('pemohon', $user);
        $permohonan->setRelation('kantor', $user->kantor);
        $permohonan->setRelation('atasan', $atasan);
        $permohonan->setRelation('executor', null);

        return $permohonan;
    }
}
