<?php

namespace App\Notifications;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PermohonanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Permohonan $permohonan,
        private readonly string     $type,
        private readonly string     $pesan,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => $this->type,
            'permohonan_id'  => $this->permohonan->id,
            'nomor_dokumen'  => $this->permohonan->nomor_dokumen,
            'jenis'          => $this->permohonan->jenis_permohonan?->label(),
            'nama_pemohon'   => $this->permohonan->nama_pemohon,
            'pesan'          => $this->pesan,
        ];
    }
}
