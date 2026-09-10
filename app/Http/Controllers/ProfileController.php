<?php

namespace App\Http\Controllers;

use App\Enums\AksiAudit;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
}
