<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AksiAudit;
use App\Enums\RoleUser;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Role;
use App\Models\User;
use App\Services\AdminUserService;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private AdminUserService $adminUserService) {}

    // ── Index ─────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = User::with(['kantor', 'jabatan', 'roles'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', "%{$s}%")
                    ->orWhere('nik', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
            );
        }
        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'aktif'                => $query->where('is_active', true)
                    ->whereNull('suspended_at')
                    ->whereNull('locked_at'),
                'nonaktif'            => $query->where('is_active', false),
                'suspended'           => $query->whereNotNull('suspended_at'),
                'locked'              => $query->whereNotNull('locked_at'),
                'pending_verifikasi'  => $query->where('email_verified', false)->where('is_active', true),
                default               => null,
            };
        }
        if ($request->filled('online')) {
            $threshold = now()->subMinutes(5)->timestamp;
            $onlineUserIds = DB::table('sessions')
                ->where('last_activity', '>=', $threshold)
                ->whereNotNull('user_id')
                ->pluck('user_id');
            if ($request->online === '1') {
                $query->whereIn('id', $onlineUserIds);
            } else {
                $query->whereNotIn('id', $onlineUserIds);
            }
        }

        // Ambil user ID yang sedang online untuk badge
        $onlineThreshold = now()->subMinutes(5)->timestamp;
        $onlineIds = DB::table('sessions')
            ->where('last_activity', '>=', $onlineThreshold)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();

        $users  = $query->paginate(20)->withQueryString();
        $kantor = Kantor::where('is_active', true)->orderBy('nama')->get();
        $roles  = RoleUser::cases();

        // KPI counts untuk header
        $kpi = [
            'total'              => User::count(),
            'aktif'              => User::where('is_active', true)->whereNull('suspended_at')->count(),
            'online'             => count($onlineIds),
            'pending_verifikasi' => User::where('email_verified', false)->where('is_active', true)->count(),
            'suspended'          => User::whereNotNull('suspended_at')->count(),
            'locked'             => User::whereNotNull('locked_at')->count(),
        ];

        return view('admin.users.index', compact('users', 'kantor', 'roles', 'onlineIds', 'kpi'));
    }

    // ── Pending Registration ──────────────────────────────────────────────

    public function pending(): View
    {
        $users = User::with(['kantor', 'jabatan', 'roles'])
            ->where('email_verified', false)
            ->where('is_active', true)
            ->oldest()
            ->paginate(20);

        return view('admin.users.pending', compact('users'));
    }

    // ── Create / Store ────────────────────────────────────────────────────

    public function create(): View
    {
        $kantor  = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatan = Jabatan::aktif()->get();
        $roles   = RoleUser::cases();

        return view('admin.users.create', compact('kantor', 'jabatan', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:150', 'regex:/^[A-Za-z\s\.\,\-\']+$/'],
            'nik'            => ['required', 'string', 'regex:/^AP\d{9}$/', 'unique:users,nik'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', Password::min(8)->letters()->numbers()],
            'kantor_id'      => ['required', 'exists:kantors,id'],
            'jabatan_id'     => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => ['nullable', 'string', 'max:100'],
            'roles'          => ['required', 'array', 'min:1'],
            'roles.*'        => ['string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'           => strtoupper(trim($request->name)),
            'nik'            => strtoupper($request->nik),
            'email'          => strtolower($request->email),
            'password'       => Hash::make($request->password),
            'kantor_id'      => $request->kantor_id,
            'jabatan_id'     => $request->jabatan_id,
            'jabatan_custom' => $request->jabatan_custom ? strtoupper(trim($request->jabatan_custom)) : null,
            'is_active'      => true,
            'email_verified' => true,
        ]);

        $roleIds = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->attach($roleIds->mapWithKeys(
            fn($id) => [$id => ['assigned_at' => now()]]
        )->all());

        AuditService::log(AksiAudit::USER_CREATED, auth()->id(), $user, null, [
            'roles' => $request->roles,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil dibuat.");
    }

    // ── Show (User 360°) ──────────────────────────────────────────────────

    public function show(User $user): View
    {
        $user->load('kantor', 'jabatan', 'roles');

        // Sessions aktif
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($s) {
                $s->is_online   = $s->last_activity >= now()->subMinutes(5)->timestamp;
                $s->is_idle     = !$s->is_online && $s->last_activity >= now()->subMinutes(30)->timestamp;
                $s->last_active = \Carbon\Carbon::createFromTimestamp($s->last_activity);
                return $s;
            });

        // Semua permohonan
        $permohonan = $user->permohonan()->with('kantor')->latest()->paginate(10);

        // Activity log (dari audit_logs)
        $activity = \App\Models\AuditLog::where('user_id', $user->id)
            ->latest('created_at')
            ->take(30)
            ->get();

        // Security info
        $securityLog = \App\Models\AuditLog::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->whereIn('aksi', [
                    AksiAudit::USER_LOGIN_FAILED->value,
                    AksiAudit::USER_ACCOUNT_LOCKED->value,
                    AksiAudit::USER_ACCOUNT_UNLOCKED->value,
                    AksiAudit::USER_FORCE_LOGOUT->value,
                    AksiAudit::USER_LOGOUT_ALL->value,
                    AksiAudit::USER_SUSPENDED->value,
                    AksiAudit::USER_UNSUSPENDED->value,
                    AksiAudit::USER_MANUAL_VERIFIED->value,
                ]);
        })
            ->orWhere(function ($q) use ($user) {
                // Aksi yang dilakukan TERHADAP user ini oleh admin
                $q->where('subject_type', User::class)
                    ->where('subject_id', $user->id);
            })
            ->latest('created_at')
            ->take(20)
            ->get();

        // Permohonan summary
        $permohonanSummary = $user->permohonan()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.users.show', compact(
            'user',
            'sessions',
            'permohonan',
            'activity',
            'securityLog',
            'permohonanSummary'
        ));
    }

    // ── Edit / Update ─────────────────────────────────────────────────────

    public function edit(User $user): View
    {
        $user->load('roles');
        $kantor    = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatan   = Jabatan::aktif()->get();
        $roles     = RoleUser::cases();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'kantor', 'jabatan', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'unique:users,email,' . $user->id],
            'kantor_id'      => ['required', 'exists:kantors,id'],
            'jabatan_id'     => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => ['nullable', 'string', 'max:100'],
            'is_active'      => ['required', 'boolean'],
            'roles'          => ['required', 'array', 'min:1'],
            'roles.*'        => ['string', 'exists:roles,name'],
        ]);

        $before = [
            'roles'     => $user->roles->pluck('name')->toArray(),
            'is_active' => $user->is_active,
        ];

        $user->update([
            'name'           => strtoupper(trim($request->name)),
            'email'          => strtolower($request->email),
            'kantor_id'      => $request->kantor_id,
            'jabatan_id'     => $request->jabatan_id,
            'jabatan_custom' => $request->jabatan_custom ? strtoupper(trim($request->jabatan_custom)) : null,
            'is_active'      => $request->boolean('is_active'),
        ]);

        $roleIds = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->sync($roleIds->mapWithKeys(
            fn($id) => [$id => ['assigned_at' => now()]]
        )->all());

        AuditService::log(AksiAudit::USER_ROLE_ASSIGNED, auth()->id(), $user, $before, [
            'roles'     => $request->roles,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    // ── Reset Password ────────────────────────────────────────────────────

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        AuditService::log(AksiAudit::USER_PASSWORD_RESET, auth()->id(), $user, null, [
            'target_user_id' => $user->id,
        ]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    // ── Manual Verify ─────────────────────────────────────────────────────

    public function manualVerify(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($user->email_verified) {
            return back()->with('error', 'User sudah terverifikasi.');
        }

        $this->adminUserService->manualVerify($user, auth()->user(), $request->reason);

        return back()->with('success', "Akun {$user->name} berhasil diverifikasi secara manual.");
    }

    // ── Reject Registration ───────────────────────────────────────────────

    public function rejectRegistration(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($user->email_verified) {
            return back()->with('error', 'User sudah aktif, tidak bisa ditolak.');
        }

        $before = ['is_active' => $user->is_active, 'email_verified' => $user->email_verified];
        $user->update(['is_active' => false]);

        AuditService::log(AksiAudit::USER_REJECTED, auth()->id(), $user, $before, [
            'reason' => $request->reason,
        ]);

        return redirect()->route('admin.users.pending')
            ->with('success', "Registrasi {$user->name} ditolak.");
    }

    // ── Suspend / Unsuspend ───────────────────────────────────────────────

    public function suspend(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin tidak dapat disuspend.');
        }

        $this->adminUserService->suspend($user, auth()->user(), $request->reason);

        return back()->with('success', "Akun {$user->name} berhasil disuspend.");
    }

    public function unsuspend(Request $request, User $user): RedirectResponse
    {
        $this->adminUserService->unsuspend($user, auth()->user());

        return back()->with('success', "Suspend akun {$user->name} berhasil dicabut.");
    }

    // ── Lock / Unlock ─────────────────────────────────────────────────────

    public function lock(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Super Admin tidak dapat dikunci.');
        }

        $this->adminUserService->lock($user, auth()->user(), $request->reason);

        return back()->with('success', "Akun {$user->name} berhasil dikunci.");
    }

    public function unlock(Request $request, User $user): RedirectResponse
    {
        $this->adminUserService->unlock($user, auth()->user());

        return back()->with('success', "Kunci akun {$user->name} berhasil dibuka.");
    }

    // ── Force Logout ──────────────────────────────────────────────────────

    public function forceLogout(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'session_id' => ['required', 'string'],
            'reason'     => ['required', 'string', 'max:255'],
        ]);

        $this->adminUserService->forceLogoutSession(
            $user,
            auth()->user(),
            $request->session_id,
            $request->reason
        );

        return back()->with('success', 'Session berhasil diterminasi.');
    }

    public function forceLogoutAll(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $this->adminUserService->forceLogoutAll($user, auth()->user(), $request->reason);

        return back()->with('success', "Semua session {$user->name} berhasil diterminasi.");
    }
}
