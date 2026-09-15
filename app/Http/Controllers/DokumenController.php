<?php

namespace App\Http\Controllers;

use App\Enums\AksiAudit;
use App\Enums\DocumentRenderMode;
use App\Models\Permohonan;
use App\Services\AuditService;
use App\Services\DocumentPreviewFactory;
use App\Services\DocumentRenderer;
use Illuminate\View\View;

class DokumenController extends Controller
{
    public function __construct(
        private DocumentRenderer $renderer,
        private DocumentPreviewFactory $previewFactory,
    ) {}

    /**
     * Preview dokumen di browser — standalone HTML page.
     * Dipakai di step 3 wizard, halaman detail, dan halaman approval.
     */
    public function preview(Permohonan $permohonan): View
    {
        // Cek akses: pemohon sendiri, atasan yang ditunjuk,
        // dirut, it_staff, atau super admin
        $user = auth()->user();

        $boleh = $user->id === $permohonan->pemohon_id
            || $user->id === $permohonan->atasan_id
            || $user->isDirut()
            || $user->isItStaff()
            || $user->isSuperAdmin();

        if (! $boleh) {
            abort(403);
        }

        AuditService::log(
            AksiAudit::DOKUMEN_DILIHAT,
            $user->id,
            $permohonan,
            null,
            ['channel' => 'interactive'],
            $permohonan->nomor_dokumen,
        );

        $data = $this->renderer->prepare($permohonan, DocumentRenderMode::INTERACTIVE, $user);

        // Render sebagai halaman standalone (bukan layout app)
        return view('dokumen.fruid', $data);
    }

    /**
     * Render preview wizard dari payload session, tanpa membuat record draft.
     */
    public function previewFromSession(): View
    {
        $preview = session('permohonan.preview');
        $user = auth()->user();

        if (! is_array($preview) || ($preview['user_id'] ?? null) !== $user->id) {
            abort(404);
        }

        $permohonan = $this->previewFactory->make($user, $preview['data'] ?? []);

        return view('dokumen.fruid', $this->renderer->prepare(
            $permohonan,
            DocumentRenderMode::INTERACTIVE,
            $user,
        ));
    }
}
