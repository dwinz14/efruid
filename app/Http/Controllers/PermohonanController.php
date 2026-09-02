<?php

namespace App\Http\Controllers;

use App\Enums\AccessLevel;
use App\Enums\JenisPermohonan;
use App\Enums\RoleUser;
use App\Enums\StatusPermohonan;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Permohonan;
use App\Models\User;
use App\Services\PermohonanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PermohonanController extends Controller
{
    public function __construct(private PermohonanService $service) {}

    // ── Daftar permohonan milik pemohon ───────────────────────────────────

    public function index(Request $request): View
    {
        $query = Permohonan::where('pemohon_id', auth()->id())
            ->with(['kantor'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $permohonan = $query->paginate(10)->withQueryString();
        $statuses = StatusPermohonan::cases();

        return view('permohonan.index', compact('permohonan', 'statuses'));
    }

    // ── Step 1: Pilih jenis form (wizard entry) ───────────────────────────

    public function create(): View
    {
        return view('permohonan.create-step1');
    }

    // ── Step 2: Form isian data ───────────────────────────────────────────

    public function createStep2(Request $request): View|RedirectResponse
    {
        $request->validate([
            'form_type' => ['required', 'in:normal,rangkap'],
        ]);

        $user = auth()->user()->load('kantor', 'jabatan');
        $pemohonLevel = $user->jabatan_level;

        // Resolve level atasan yang dibutuhkan
        $targetAtasanLevels = $user->jabatan?->resolveTargetAtasanLevels();

        // Build query atasan berdasarkan level jabatan pemohon
        $atasans = collect();

        if ($targetAtasanLevels !== null) {
            $query = User::with('jabatan')
                ->whereHas(
                    'jabatan',
                    fn($q) =>
                    $q->whereIn('level', $targetAtasanLevels)
                )
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->orderBy(
                    Jabatan::select('level')
                        ->whereColumn('jabatans.id', 'users.jabatan_id')
                )
                ->orderBy('name');

            // L5 dan L4 → atasan di kantor yang sama
            // L3 dan L2 → atasan (Dirut, level 1) lintas kantor
            if (in_array($pemohonLevel, [Jabatan::LEVEL_STAFF, Jabatan::LEVEL_KASIE])) {
                $query->where('kantor_id', $user->kantor_id);
            }
            // L3/L2 → tidak filter kantor (Dirut ada di pusat)

            $atasans = $query->get();
        }
        // Jika targetAtasanLevel === null (Dirut), $atasans tetap kosong

        $kantors      = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatans     = Jabatan::aktif()->get();
        $formType     = $request->form_type;
        $accessLevels = AccessLevel::cases();
        $jenisList    = JenisPermohonan::cases();

        // Load draft jika ada
        $draft = null;
        if ($request->filled('draft_id')) {
            $draft = Permohonan::where('id', $request->draft_id)
                ->where('pemohon_id', auth()->id())
                ->where('status', StatusPermohonan::DRAFT->value)
                ->first();
        }

        // Kirim info ke view: apakah pemohon ini Dirut (tidak perlu atasan)
        $pemohonIsDirut = $user->isDirutByJabatan();

        return view('permohonan.create-step2', compact(
            'user',
            'kantors',
            'jabatans',
            'atasans',
            'formType',
            'accessLevels',
            'jenisList',
            'draft',
            'pemohonIsDirut',
            'targetAtasanLevels'
        ));
    }

    // ── Step 3: Preview dokumen ───────────────────────────────────────────

    public function createStep3(Request $request): View|RedirectResponse
    {
        // Validasi data dari step 2
        $validated = $this->validateStep2($request);
        if ($validated instanceof RedirectResponse) {
            return $validated;
        }

        $user = auth()->user()->load('kantor', 'jabatan');

        // Simpan sebagai draft sementara atau update draft yang ada
        $permohonan = null;
        if ($request->filled('permohonan_id')) {
            $permohonan = Permohonan::where('id', $request->permohonan_id)
                ->where('pemohon_id', auth()->id())
                ->where('status', StatusPermohonan::DRAFT->value)
                ->first();
        }

        $data = $request->only([
            'form_type',
            'kantor_id',
            'user_id_ussi',
            'jenis_permohonan',
            'tipe_perubahan',
            'jabatan_lama',
            'jabatan_baru',
            'alasan_perubahan',
            'tgl_permanen',
            'tgl_mulai',
            'tgl_selesai',
            'tgl_nonaktif',
            'access_level',
            'atasan_id',
        ]);

        if ($permohonan) {
            $permohonan = $this->service->updateDraft($permohonan, $data);
        } else {
            $permohonan = $this->service->createDraft($user, $data);
        }

        // Load relasi untuk preview
        $permohonan->load('kantor', 'atasan', 'pemohon');
        $atasan = $permohonan->atasan;

        return view('permohonan.create-step3', compact('permohonan', 'user', 'atasan'));
    }

    // ── Submit permohonan ─────────────────────────────────────────────────

    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'permohonan_id' => ['required', 'exists:permohonan,id'],
        ]);

        $permohonan = Permohonan::findOrFail($request->permohonan_id);
        $user = auth()->user();

        $this->authorize('submit', $permohonan);

        // Warning jika belum ada TTD (tidak blokir, hanya dicatat)
        $noTtd = ! $user->signature_path;

        $permohonan = $this->service->submit($permohonan, $user);

        $message = $noTtd
            ? 'Permohonan berhasil disubmit. Catatan: Anda belum memiliki tanda tangan digital di profil.'
            : 'Permohonan berhasil disubmit dan menunggu persetujuan atasan.';

        return redirect()->route('permohonan.show', $permohonan)
            ->with('success', $message);
    }

    // ── Simpan draft ──────────────────────────────────────────────────────

    public function saveDraft(Request $request): RedirectResponse
    {
        $validated = $this->validateStep2($request, isDraft: true);
        if ($validated instanceof RedirectResponse) {
            return $validated;
        }

        $user = auth()->user();
        $data = $request->only([
            'form_type',
            'kantor_id',
            'user_id_ussi',
            'jenis_permohonan',
            'tipe_perubahan',
            'jabatan_lama',
            'jabatan_baru',
            'alasan_perubahan',
            'tgl_permanen',
            'tgl_mulai',
            'tgl_selesai',
            'tgl_nonaktif',
            'access_level',
            'atasan_id',
        ]);

        if ($request->filled('permohonan_id')) {
            $permohonan = Permohonan::where('id', $request->permohonan_id)
                ->where('pemohon_id', $user->id)
                ->where('status', StatusPermohonan::DRAFT->value)
                ->firstOrFail();
            $this->service->updateDraft($permohonan, $data);
        } else {
            $permohonan = $this->service->createDraft($user, $data);
        }

        return redirect()->route('permohonan.step2', [
            'form_type' => $permohonan->form_type->value,
            'draft_id' => $permohonan->id,
        ])->with('success', 'Draft berhasil disimpan. Anda dapat melanjutkan pengisian.');
    }

    // ── Detail permohonan ─────────────────────────────────────────────────

    public function show(Permohonan $permohonan): View
    {
        $this->authorize('view', $permohonan);

        $permohonan->load('kantor', 'pemohon', 'atasan', 'approvalLogs.user');

        return view('permohonan.show', compact('permohonan'));
    }

    // ── Edit draft ────────────────────────────────────────────────────────

    public function edit(Permohonan $permohonan): RedirectResponse
    {
        $this->authorize('update', $permohonan);

        return redirect()->route('permohonan.step2', [
            'form_type' => $permohonan->form_type->value,
            'draft_id' => $permohonan->id,
        ]);
    }

    // ── Batalkan permohonan ───────────────────────────────────────────────

    public function cancel(Request $request, Permohonan $permohonan): RedirectResponse
    {
        $this->authorize('cancel', $permohonan);

        $this->service->cancel($permohonan, auth()->user());

        return redirect()->route('permohonan.index')
            ->with('success', 'Permohonan berhasil dibatalkan.');
    }

    // ── Download PDF ──────────────────────────────────────────────────────

    public function downloadPdf(Permohonan $permohonan): StreamedResponse
    {
        $this->authorize('view', $permohonan);

        if (! $permohonan->pdf_path || ! Storage::exists($permohonan->pdf_path)) {
            abort(404, 'Dokumen PDF belum tersedia.');
        }

        $nama = 'FRUID-' . str_replace('/', '-', $permohonan->nomor_dokumen ?? $permohonan->id);

        return Storage::download($permohonan->pdf_path, $nama . '.pdf');
    }

    // ── Helper: validasi data step 2 ─────────────────────────────────────

    private function validateStep2(Request $request, bool $isDraft = false): mixed
    {
        $rules = [
            'form_type'        => ['required', 'in:normal,rangkap'],
            'kantor_id'        => ['required', 'exists:kantors,id'],
            'user_id_ussi'     => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'jenis_permohonan' => ['required', 'in:pendaftaran,perubahan,nonaktif'],
            'access_level'     => ['required', 'in:DIREKSI,ADMINISTRATOR,USER'],
            // atasan_id nullable — Dirut tidak punya atasan
            'atasan_id'        => ['nullable', 'exists:users,id'],
        ];

        if ($request->jenis_permohonan === 'perubahan') {
            $rules['tipe_perubahan'] = ['required', 'in:permanen,sementara'];
            $rules['jabatan_lama']   = ['required', 'string', 'max:150'];
            $rules['jabatan_baru']   = ['required', 'string', 'max:150'];

            if ($request->tipe_perubahan === 'permanen') {
                $rules['tgl_permanen'] = ['required', 'date'];
            }
            if ($request->tipe_perubahan === 'sementara') {
                $rules['tgl_mulai']   = ['required', 'date'];
                $rules['tgl_selesai'] = ['required', 'date', 'after:tgl_mulai'];
            }
        }

        if ($request->jenis_permohonan === 'nonaktif') {
            $rules['tgl_nonaktif'] = ['required', 'date'];
        }

        $messages = [
            'user_id_ussi.regex' => 'User ID hanya boleh berisi huruf, angka, underscore, dan strip.',
            'tgl_selesai.after'  => 'Tanggal selesai harus setelah tanggal mulai.',
        ];

        $request->validate($rules, $messages);

        return true;
    }
}
