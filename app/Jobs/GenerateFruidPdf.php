<?php

namespace App\Jobs;

use App\Enums\AksiAudit;
use App\Models\Permohonan;
use App\Services\AuditService;
use App\Services\PdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateFruidPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(
        private readonly int $permohonanId,
        private readonly int $executorId,
    ) {}

    public function handle(PdfService $pdfService): void
    {
        $permohonan = Permohonan::find($this->permohonanId);

        if (! $permohonan) {
            Log::warning("GenerateFruidPdf: permohonan #{$this->permohonanId} tidak ditemukan.");
            return;
        }

        try {
            $path = $pdfService->generate($permohonan);

            $permohonan->update(['pdf_path' => $path]);

            AuditService::log(
                AksiAudit::PDF_GENERATED,
                $this->executorId,
                $permohonan,
                null,
                ['pdf_path' => $path],
                $permohonan->nomor_dokumen,
            );
        } catch (\Throwable $e) {
            Log::error("GenerateFruidPdf gagal untuk #{$this->permohonanId}: " . $e->getMessage());
            throw $e; // re-throw agar queue mencatat sebagai failed
        }
    }
}
