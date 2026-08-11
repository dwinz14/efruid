<?php

namespace App\Services;

use App\Enums\AksiAudit;
use App\Enums\StatusPermohonan;
use App\Models\Kantor;
use App\Models\Permohonan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermohonanService
{
    // ── Buat draft baru ───────────────────────────────────────────────────

    public function createDraft(User $pemohon, array $data): Permohonan
    {
        $permohonan = Permohonan::create([
            'form_type' => $data['form_type'],
            'tanggal_permohonan' => Carbon::today(),
            'pemohon_id' => $pemohon->id,
            'kantor_id' => $data['kantor_id'],
            'nama_pemohon' => $pemohon->name,
            'jabatan_pemohon' => $pemohon->jabatan_label,
            'nik_pemohon' => $pemohon->nik,
            'user_id_ussi' => $data['user_id_ussi'],
            'jenis_permohonan' => $data['jenis_permohonan'],
            'tipe_perubahan' => $data['tipe_perubahan'] ?? null,
            'jabatan_lama' => $data['jabatan_lama'] ?? null,
            'jabatan_baru' => $data['jabatan_baru'] ?? null,
            'alasan_perubahan' => $data['alasan_perubahan'] ?? null,
            'tgl_permanen' => $data['tgl_permanen'] ?? null,
            'tgl_mulai' => $data['tgl_mulai'] ?? null,
            'tgl_selesai' => $data['tgl_selesai'] ?? null,
            'tgl_nonaktif' => $data['tgl_nonaktif'] ?? null,
            'access_level' => $data['access_level'],
            'atasan_id' => $data['atasan_id'] ?? null,
            'nama_atasan_ttd' => isset($data['atasan_id'])
                ? User::find($data['atasan_id'])?->name
                : null,
            'status' => StatusPermohonan::DRAFT,
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_CREATED,
            $pemohon->id,
            $permohonan,
        );

        return $permohonan;
    }

    // ── Update draft yang sudah ada ───────────────────────────────────────

    public function updateDraft(Permohonan $permohonan, array $data): Permohonan
    {
        // Snapshot nama atasan jika atasan_id berubah
        $namaAtasan = null;
        if (! empty($data['atasan_id'])) {
            $namaAtasan = User::find($data['atasan_id'])?->name;
        }

        $permohonan->update([
            'form_type' => $data['form_type'],
            'kantor_id' => $data['kantor_id'],
            'user_id_ussi' => $data['user_id_ussi'],
            'jenis_permohonan' => $data['jenis_permohonan'],
            'tipe_perubahan' => $data['tipe_perubahan'] ?? null,
            'jabatan_lama' => $data['jabatan_lama'] ?? null,
            'jabatan_baru' => $data['jabatan_baru'] ?? null,
            'alasan_perubahan' => $data['alasan_perubahan'] ?? null,
            'tgl_permanen' => $data['tgl_permanen'] ?? null,
            'tgl_mulai' => $data['tgl_mulai'] ?? null,
            'tgl_selesai' => $data['tgl_selesai'] ?? null,
            'tgl_nonaktif' => $data['tgl_nonaktif'] ?? null,
            'access_level' => $data['access_level'],
            'atasan_id' => $data['atasan_id'] ?? null,
            'nama_atasan_ttd' => $namaAtasan,
        ]);

        return $permohonan;
    }

    // ── Submit permohonan (DRAFT → PENDING_ATASAN) ────────────────────────

    public function submit(Permohonan $permohonan, User $pemohon): Permohonan
    {
        $nomorDokumen = $this->generateNomorDokumen($permohonan);

        // Copy snapshot TTD pemohon jika ada
        $ttdPemohonPath = null;
        if ($pemohon->signature_path && Storage::exists($pemohon->signature_path)) {
            $ttdPemohonPath = $this->copySignatureSnapshot($pemohon, $permohonan->id, 'pemohon');
        }

        $permohonan->update([
            'status'           => StatusPermohonan::PENDING_ATASAN,
            'nomor_dokumen'    => $nomorDokumen,
            'nama_pemohon'     => $pemohon->name,
            'jabatan_pemohon'  => $pemohon->jabatan_label,
            'nik_pemohon'      => $pemohon->nik,
            'ttd_pemohon_path' => $ttdPemohonPath,
        ]);

        AuditService::log(
            AksiAudit::PERMOHONAN_SUBMITTED,
            $pemohon->id,
            $permohonan,
            null,
            ['nomor_dokumen' => $nomorDokumen, 'status' => StatusPermohonan::PENDING_ATASAN->value],
            $nomorDokumen,
        );

        return $permohonan->fresh();
    }

    // ── Generate PDF dokumen FRUID ────────────────────────────────────────

    public function generatePdf(Permohonan $permohonan): string
    {
        $permohonan->load('kantor', 'atasan', 'pemohon');

        $pdf = Pdf::loadView('permohonan.partials.document-preview', ['p' => $permohonan])
            ->setPaper('A4', 'portrait');

        $path = "pdf/permohonan/{$permohonan->id}.pdf";
        Storage::put($path, $pdf->output());

        $permohonan->update(['pdf_path' => $path]);

        AuditService::log(
            AksiAudit::PDF_GENERATED,
            $permohonan->pemohon_id,
            $permohonan,
            null,
            ['pdf_path' => $path],
            $permohonan->nomor_dokumen,
        );

        return $path;
    }

    // ── Batalkan permohonan ───────────────────────────────────────────────

    public function cancel(Permohonan $permohonan, User $pemohon): void
    {
        $statusLama = $permohonan->status->value;

        $permohonan->update(['status' => StatusPermohonan::CANCELLED]);

        AuditService::log(
            AksiAudit::PERMOHONAN_CANCELLED,
            $pemohon->id,
            $permohonan,
            ['status' => $statusLama],
            ['status' => StatusPermohonan::CANCELLED->value],
            $permohonan->nomor_dokumen,
        );
    }

    // ── Generate nomor dokumen (race-condition safe) ───────────────────────

    private function generateNomorDokumen(Permohonan $permohonan): string
    {
        return DB::transaction(function () use ($permohonan) {
            // Lock untuk cegah concurrent insert
            $kantor = Kantor::lockForUpdate()->find($permohonan->kantor_id);
            $tahun = Carbon::today()->format('Y');
            $kode = strtoupper($kantor->kode);

            // Hitung sequence: berapa permohonan dari kantor ini di tahun ini
            $count = Permohonan::whereYear('tanggal_permohonan', $tahun)
                ->where('kantor_id', $permohonan->kantor_id)
                ->whereNotIn('status', [
                    StatusPermohonan::DRAFT->value,
                    StatusPermohonan::CANCELLED->value,
                ])
                ->lockForUpdate()
                ->count();

            $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            return "FRUID/{$kode}/{$tahun}/{$seq}";
        });
    }

    // ── Copy snapshot TTD ke folder permohonan ────────────────────────────

    private function copySignatureSnapshot(User $user, int $permohonanId, string $role): string
    {
        $src = $user->signature_path;
        $dest = "signatures/snapshots/{$permohonanId}_{$role}.png";

        if (Storage::exists($src)) {
            Storage::copy($src, $dest);

            return $dest;
        }

        return $src;
    }
}
