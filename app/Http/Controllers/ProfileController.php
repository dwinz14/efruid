<?php

namespace App\Http\Controllers;

use App\Enums\AksiAudit;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // ── Tampilkan halaman profil ──────────────────────────────────────────

    public function edit(): View
    {
        $user     = auth()->user()->load('kantor', 'jabatan', 'roles');
        $kantors  = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatans = Jabatan::aktif()->get();

        return view('profile.edit', compact('user', 'kantors', 'jabatans'));
    }

    // ── Update data profil ────────────────────────────────────────────────

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name'      => ['required', 'string', 'max:150', 'regex:/^[A-Za-z\s\.\,\-\']+$/'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'kantor_id' => ['required', 'exists:kantors,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => [
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    $jabatan = Jabatan::find($request->jabatan_id);
                    if ($jabatan?->is_lainnya && empty($value)) {
                        $fail('Nama jabatan wajib diisi untuk pilihan Lainnya.');
                    }
                },
            ],
        ], [
            'name.regex'   => 'Nama hanya boleh mengandung huruf, spasi, dan tanda baca dasar.',
            'email.unique' => 'Email ini sudah digunakan akun lain.',
        ]);

        $before = [
            'name'       => $user->name,
            'email'      => $user->email,
            'kantor_id'  => $user->kantor_id,
            'jabatan_id' => $user->jabatan_id,
        ];

        // Jika email berubah, reset verifikasi
        $emailChanged = $user->email !== strtolower($request->email);

        $user->update([
            'name'           => strtoupper(trim($request->name)),
            'email'          => strtolower($request->email),
            'kantor_id'      => $request->kantor_id,
            'jabatan_id'     => $request->jabatan_id,
            'jabatan_custom' => $request->jabatan_custom
                ? strtoupper(trim($request->jabatan_custom))
                : null,
            'email_verified' => $emailChanged ? false : $user->email_verified,
        ]);

        AuditService::log(
            AksiAudit::USER_REGISTER, // reuse — nanti bisa tambah USER_PROFILE_UPDATED di Fase 10
            $user->id,
            $user,
            $before,
            ['name' => $user->name, 'email' => $user->email]
        );

        if ($emailChanged) {
            return redirect()->route('verification.notice')
                ->with('success', 'Profil diperbarui. Verifikasi email baru Anda.');
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ── Ganti password ────────────────────────────────────────────────────

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withFragment('password');
        }

        $user->update(['password' => Hash::make($request->password)]);

        AuditService::auth(AksiAudit::USER_PASSWORD_RESET, $user->id);

        return back()->with('success', 'Password berhasil diubah.')->withFragment('password');
    }

    // ── Upload tanda tangan (file PNG) ────────────────────────────────────

    public function uploadSignature(Request $request): RedirectResponse
    {
        $request->validate([
            'signature_file' => [
                'required',
                'file',
                'mimes:png',
                'max:2048', // 2MB
            ],
        ], [
            'signature_file.required' => 'File tanda tangan wajib dipilih.',
            'signature_file.mimes'    => 'File harus berformat PNG.',
            'signature_file.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $user = auth()->user();

        // Hapus file lama jika ada
        $this->deleteOldSignature($user);

        // Simpan dengan nama UUID — tidak bisa ditebak
        $filename = Str::uuid() . '.png';
        $request->file('signature_file')->storeAs('signatures', $filename);

        $user->update(['signature_path' => 'signatures/' . $filename]);

        AuditService::auth(AksiAudit::USER_SIGNATURE, $user->id, [
            'method' => 'upload',
        ]);

        return back()->with('success', 'Tanda tangan berhasil disimpan.')->withFragment('signature');
    }

    // ── Simpan tanda tangan dari canvas draw ──────────────────────────────

    public function saveSignatureCanvas(Request $request): RedirectResponse
    {
        $request->validate([
            'signature_data' => ['required', 'string'],
        ]);

        // Validasi format base64 PNG
        $data = $request->signature_data;
        if (! str_starts_with($data, 'data:image/png;base64,')) {
            return back()
                ->withErrors(['signature_data' => 'Format tanda tangan tidak valid.'])
                ->withFragment('signature');
        }

        $user = auth()->user();

        // Hapus file lama
        $this->deleteOldSignature($user);

        // Decode base64 dan simpan
        $base64 = substr($data, strlen('data:image/png;base64,'));
        $binary = base64_decode($base64);

        if ($binary === false || strlen($binary) < 100) {
            return back()
                ->withErrors(['signature_data' => 'Data tanda tangan tidak valid atau kosong.'])
                ->withFragment('signature');
        }

        $filename = Str::uuid() . '.png';
        Storage::put('signatures/' . $filename, $binary);

        $user->update(['signature_path' => 'signatures/' . $filename]);

        AuditService::auth(AksiAudit::USER_SIGNATURE, $user->id, [
            'method' => 'canvas',
        ]);

        return back()->with('success', 'Tanda tangan berhasil disimpan.')->withFragment('signature');
    }

    // ── Hapus tanda tangan ────────────────────────────────────────────────

    public function deleteSignature(): RedirectResponse
    {
        $user = auth()->user();
        $this->deleteOldSignature($user);
        $user->update(['signature_path' => null]);

        return back()->with('success', 'Tanda tangan dihapus.')->withFragment('signature');
    }

    // ── Serve file tanda tangan (bukan dari public/) ──────────────────────

    public function showSignature(User $user): Response
    {
        // User hanya bisa lihat TTD milik sendiri,
        // kecuali Super Admin (untuk keperluan admin)
        $viewer = auth()->user();
        if ($viewer->id !== $user->id && ! $viewer->isSuperAdmin()) {
            abort(403);
        }

        if (! $user->signature_path || ! Storage::exists($user->signature_path)) {
            abort(404);
        }

        $file    = Storage::get($user->signature_path);
        $mime    = 'image/png';

        return response($file, 200)->header('Content-Type', $mime)
            ->header('Cache-Control', 'private, max-age=3600');
    }

    // ── Helper: hapus file signature lama dari storage ────────────────────

    private function deleteOldSignature(User $user): void
    {
        if ($user->signature_path && Storage::exists($user->signature_path)) {
            Storage::delete($user->signature_path);
        }
    }
}
