<?php

namespace App\Http\Controllers;

use App\Enums\AccessLevel;
use App\Enums\JenisPermohonan;
use App\Enums\StatusPermohonan;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Permohonan;
use App\Models\User;
use App\Services\DocumentPreviewFactory;
use App\Services\PermohonanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermohonanController extends Controller
{
    public function __construct(
        private PermohonanService $service,
        private DocumentPreviewFactory $previewFactory,
    ) {}

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
        $activePermohonan = Permohonan::getActiveFor(auth()->user());

        return view('permohonan.index', compact('permohonan', 'statuses', 'activePermohonan'));
    }

    // ── Step 1: Pilih jenis form (wizard entry) ───────────────────────────

    public function create(): View|RedirectResponse
    {
        if (Permohonan::hasActiveDraftFor(auth()->user())) {
            return redirect()->route('permohonan.index')
                ->with('warning', 'Anda masih memiliki permohonan yang sedang aktif. Selesaikan atau batalkan permohonan tersebut sebelum membuat permohonan baru.');
        }
        session()->forget('permohonan.preview');

        return view('permohonan.create-step1');
    }

    // ── Step 2: Form isian data ───────────────────────────────────────────

    public function createStep2(Request $request): View|RedirectResponse
    {
        // Guard: hanya blok jika ini kreasi baru (bukan edit draft yang sudah ada)
        if (! $request->filled('draft_id')) {
            if (Permohonan::hasActiveDraftFor(auth()->user())) {
                return redirect()->route('permohonan.index')
                    ->with('warning', 'Anda masih memiliki permohonan yang sedang aktif. Selesaikan atau batalkan permohonan tersebut sebelum membuat permohonan baru.');
            }
        }

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
                    fn ($q) => $q->whereIn('level', $targetAtasanLevels)
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

        $kantors = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatans = Jabatan::aktif()->get();
        $formType = $request->form_type;
        $accessLevels = AccessLevel::cases();
        $jenisList = JenisPermohonan::cases();

        // Load draft jika ada. Preview yang belum disimpan diisi kembali dari
        // session agar pengguna tidak kehilangan data saat kembali mengedit.
        $draft = null;
        if ($request->filled('draft_id')) {
            $draft = Permohonan::where('id', $request->draft_id)
                ->where('pemohon_id', auth()->id())
                ->where('status', StatusPermohonan::DRAFT->value)
                ->first();
        }

        $preview = session('permohonan.preview');
        if (! $draft && is_array($preview) && ($preview['user_id'] ?? null) === auth()->id()) {
            $draft = $this->previewFactory->make($user, $preview['data'] ?? []);
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

        // Preview tidak boleh membuat atau mengubah draft. Payload disimpan
        // sementara per-sesi dan baru dipersist saat tombol Simpan Draft atau
        // Submit Permohonan dipilih secara eksplisit.
        session()->put('permohonan.preview', [
            'user_id' => $user->id,
            'draft_id' => $request->filled('permohonan_id') ? (int) $request->permohonan_id : null,
            'data' => $data,
        ]);

        $permohonan = $this->previewFactory->make($user, $data);
        $atasan = $permohonan->atasan;

        return view('permohonan.create-step3', compact('permohonan', 'user', 'atasan'));
    }

    // ── Submit permohonan ─────────────────────────────────────────────────

    public function submit(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if ($request->boolean('from_preview')) {
            $preview = session('permohonan.preview');
            abort_unless(is_array($preview) && ($preview['user_id'] ?? null) === $user->id, 422);

            $data = $preview['data'] ?? [];
            $draftId = $preview['draft_id'] ?? null;

            $permohonan = $draftId
                ? Permohonan::whereKey($draftId)->where('pemohon_id', $user->id)
                    ->where('status', StatusPermohonan::DRAFT->value)->firstOrFail()
                : null;

            if ($permohonan) {
                $permohonan = $this->service->updateDraft($permohonan, $data);
            } else {
                abort_if(Permohonan::hasActiveDraftFor($user), 409);
                $permohonan = $this->service->createDraft($user, $data);
            }
        } else {
            $request->validate([
                'permohonan_id' => ['required', 'exists:permohonan,id'],
            ]);
            $permohonan = Permohonan::findOrFail($request->permohonan_id);
        }

        $this->authorize('submit', $permohonan);
        $permohonan = $this->service->submit($permohonan, $user);
        session()->forget('permohonan.preview');

        return redirect()->route('permohonan.show', $permohonan)
            ->with('success', 'Permohonan berhasil disubmit dan menunggu persetujuan atasan.');
    }

    // ── Simpan draft ──────────────────────────────────────────────────────

    public function saveDraft(Request $request): RedirectResponse
    {
        $validated = $this->validateStep2($request, isDraft: true);
        if ($validated instanceof RedirectResponse) {
            return $validated;
        }

        // Guard: hanya blok jika ini kreasi baru (bukan update draft yang sudah ada)
        if (! $request->filled('permohonan_id')) {
            if (Permohonan::hasActiveDraftFor(auth()->user())) {
                return redirect()->route('permohonan.index')
                    ->with('warning', 'Anda masih memiliki permohonan yang sedang aktif. Selesaikan atau batalkan permohonan tersebut sebelum membuat permohonan baru.');
            }
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

        session()->forget('permohonan.preview');

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

    // ── Helper: validasi data step 2 ─────────────────────────────────────

    private function validateStep2(Request $request, bool $isDraft = false): mixed
    {

        $request->merge(['kantor_id' => auth()->user()->kantor_id]);

        if (empty($request->user_id_ussi) || $request->user_id_ussi === auth()->user()->nik) {
            $nik = auth()->user()->nik;
            $derived = strlen($nik) > 3
                ? substr($nik, 0, 2).substr($nik, 5) // AP + [skip 3 digit] + sisanya
                : $nik;
            $request->merge(['user_id_ussi' => $derived]);
        }

        // Trim jabatan_baru dari input custom (LAINNYA)
        if ($request->filled('jabatan_baru')) {
            $request->merge(['jabatan_baru' => strtoupper(trim($request->jabatan_baru))]);
        }

        $rules = [
            'form_type' => ['required', 'in:normal,rangkap'],
            'kantor_id' => ['required', 'exists:kantors,id'],
            'user_id_ussi' => ['required', 'string', 'max:30', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'jenis_permohonan' => ['required', 'in:pendaftaran,perubahan,nonaktif'],
            'access_level' => ['required', 'in:DIREKSI,ADMINISTRATOR,USER'],
            'atasan_id' => ['nullable', 'exists:users,id'],
        ];

        if ($request->jenis_permohonan === 'perubahan') {
            $rules['tipe_perubahan'] = ['required', 'in:permanen,sementara'];
            $rules['jabatan_lama'] = ['required', 'string', 'max:150'];
            $rules['jabatan_baru'] = ['required', 'string', 'max:150'];

            if ($request->tipe_perubahan === 'permanen') {
                $rules['tgl_permanen'] = ['required', 'date', 'after_or_equal:today'];
            }
            if ($request->tipe_perubahan === 'sementara') {
                $rules['tgl_mulai'] = ['required', 'date', 'after_or_equal:today'];
                $rules['tgl_selesai'] = ['required', 'date', 'after:tgl_mulai'];
            }
        }

        if ($request->jenis_permohonan === 'nonaktif') {
            $rules['tgl_nonaktif'] = ['required', 'date', 'after_or_equal:today'];
        }

        $messages = [
            'user_id_ussi.regex' => 'User ID hanya boleh berisi huruf, angka, underscore, dan strip.',
            'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'tgl_permanen.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'tgl_mulai.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'tgl_nonaktif.after_or_equal' => 'Tanggal nonaktif tidak boleh di masa lalu.',
        ];

        $request->validate($rules, $messages);

        return true;
    }
}
