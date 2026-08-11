<?php

namespace App\Services;

use App\Models\Permohonan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function __construct(private DocumentRenderer $renderer) {}

    public function generate(Permohonan $permohonan): string
    {
        // Gunakan data yang sama dengan preview
        $data = $this->renderer->prepare($permohonan);

        $pdf = Pdf::loadView('dokumen.fruid', $data)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $content = $pdf->output();
        $path    = "pdf/{$permohonan->id}.pdf";

        Storage::makeDirectory('pdf');
        Storage::put($path, $content);

        return $path;
    }
}
