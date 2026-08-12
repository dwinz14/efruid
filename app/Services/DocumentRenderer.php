<?php

namespace App\Services;

use App\Models\Permohonan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DocumentRenderer
{
    /**
     * Siapkan semua data yang dibutuhkan template fruid.blade.php.
     * Dipanggil oleh preview controller maupun PdfService.
     *
     * @param  Permohonan $p
     * @return array       Data siap pakai untuk view
     */
    public function prepare(Permohonan $p): array
    {
        $p->loadMissing('pemohon', 'kantor', 'atasan');

        $kantor    = $p->kantor?->nama ?? '—';
        $isRangkap = $p->form_type?->value === 'rangkap';
        $jenis     = $p->jenis_permohonan?->value ?? '';
        $tipePerub = $p->tipe_perubahan?->value  ?? '';

        $stamps      = $p->verification_stamps ?? [];
        $stampAtasan = collect($stamps)->firstWhere('role', 'Atasan');
        $stampDirut  = collect($stamps)->firstWhere('role', 'Direktur Utama');

        return [
            'p'            => $p,
            'tgl'          => $p->tanggal_permohonan
                ? $p->tanggal_permohonan->locale('id')->isoFormat('D MMMM Y')
                : Carbon::today()->locale('id')->isoFormat('D MMMM Y'),
            'kantorLabel'  => $kantor === 'PUSAT' ? 'PUSAT' : 'CABANG ' . $kantor,
            'kotaLabel'    => $kantor === 'PUSAT'
                ? 'Pare'
                : ucfirst(strtolower($kantor)),
            'isRangkap'    => $isRangkap,
            'jenis'        => $jenis,
            'tipePerub'    => $tipePerub,

            // Checkbox marks
            'cbPendaftaran' => $jenis === 'pendaftaran' ? '&radic;' : '&nbsp;',
            'cbPerubahan'   => $jenis === 'perubahan'   ? '&radic;' : '&nbsp;',
            'cbNonaktif'    => $jenis === 'nonaktif'    ? '&radic;' : '&nbsp;',
            'cbPermanen'    => ($jenis === 'perubahan' && $tipePerub === 'permanen')
                ? '&radic;' : '&nbsp;',
            'cbSementara'   => ($jenis === 'perubahan' && $tipePerub === 'sementara')
                ? '&radic;' : '&nbsp;',

            // Tanggal format pendek
            'tglPermanen'  => $p->tgl_permanen
                ? Carbon::parse($p->tgl_permanen)->locale('id')->isoFormat('D MMM Y')
                : '',
            'tglMulai'     => $p->tgl_mulai
                ? Carbon::parse($p->tgl_mulai)->locale('id')->isoFormat('D MMM Y')
                : '',
            'tglSelesai'   => $p->tgl_selesai
                ? Carbon::parse($p->tgl_selesai)->locale('id')->isoFormat('D MMM Y')
                : '',
            'tglNonaktif'  => $p->tgl_nonaktif
                ? Carbon::parse($p->tgl_nonaktif)->locale('id')->isoFormat('D MMM Y')
                : '',

            // TTD sebagai base64 data URI — sama untuk preview & PDF
            'ttdPemohon'   => $this->toBase64Uri($p->ttd_pemohon_path),
            'ttdAtasan'    => $this->toBase64Uri($p->ttd_atasan_path),
            'ttdDirut'     => $this->toBase64Uri($p->ttd_dirut_path),

            // Stamps
            'stamps'       => $stamps,
            'stampAtasan'  => $stampAtasan,
            'stampDirut'   => $stampDirut,
        ];
    }

    /**
     * Konversi file di storage ke data URI base64.
     * Bekerja identik di browser preview maupun dompdf.
     */
    public function toBase64Uri(?string $storagePath): ?string
    {
        if (! $storagePath) {
            return null;
        }

        if (! Storage::exists($storagePath)) {
            return null;
        }

        $binary = Storage::get($storagePath);
        return 'data:image/png;base64,' . base64_encode($binary);
    }
}
