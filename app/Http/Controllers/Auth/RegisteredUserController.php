<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AksiAudit;
use App\Enums\RoleUser;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function create(): View
    {
        return view('auth.register', [
            'kantors'  => Kantor::where('is_active', true)->orderBy('nama')->get(),
            'jabatans' => Jabatan::aktif()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:150', 'regex:/^[A-Za-z\s\.\,\-\']+$/'],
            'nik'        => ['required', 'string', 'regex:/^AP\d{9}$/', 'unique:users,nik'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'kantor_id'  => ['required', 'exists:kantors,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => [
                'nullable',
                'string',
                'max:100',
                // Wajib diisi jika jabatan yang dipilih is_lainnya = true
                function ($attribute, $value, $fail) use ($request) {
                    $jabatan = Jabatan::find($request->jabatan_id);
                    if ($jabatan?->is_lainnya && empty($value)) {
                        $fail('Nama jabatan wajib diisi untuk pilihan Lainnya.');
                    }
                },
            ],
        ], [
            'name.regex'       => 'Nama hanya boleh mengandung huruf, spasi, dan tanda baca dasar.',
            'nik.regex'        => 'Format NIK tidak valid. Harus diawali AP diikuti 9 digit angka.',
            'nik.unique'       => 'NIK ini sudah terdaftar.',
            'email.unique'     => 'Email ini sudah terdaftar.',
            'password.min'     => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
            'name'           => strtoupper(trim($request->name)),
            'nik'            => strtoupper($request->nik),
            'email'          => strtolower($request->email),
            'password'       => Hash::make($request->password),
            'kantor_id'      => $request->kantor_id,
            'jabatan_id'     => $request->jabatan_id,
            'jabatan_custom' => $request->jabatan_custom
                ? strtoupper(trim($request->jabatan_custom))
                : null,
            'is_active'      => true,
            'email_verified' => false,
        ]);

        // Assign role default: pemohon
        $pemohonRole = Role::where('name', RoleUser::PEMOHON->value)->firstOrFail();
        $user->roles()->attach($pemohonRole->id, ['assigned_at' => now()]);

        // Audit
        AuditService::auth(AksiAudit::USER_REGISTER, $user->id, [
            'kantor_id'  => $user->kantor_id,
            'jabatan_id' => $user->jabatan_id,
        ]);

        // Login dulu, baru arahkan ke verifikasi OTP
        Auth::login($user);

        // Kirim OTP
        $this->otpService->send($user, 'verify_email');
        AuditService::auth(AksiAudit::USER_OTP_SENT, $user->id, ['purpose' => 'verify_email']);

        return redirect()->route('verification.notice')
            ->with('success', 'Akun berhasil dibuat. Masukkan kode OTP yang dikirim ke email Anda.');
    }
}
