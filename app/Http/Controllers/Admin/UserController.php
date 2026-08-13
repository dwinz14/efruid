<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AksiAudit;
use App\Enums\RoleUser;
use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with(['kantor', 'roles'])
            ->latest();

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
            $query->whereHas(
                'roles',
                fn($q) =>
                $q->where('name', $request->role)
            );
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users   = $query->paginate(15)->withQueryString();
        $kantor = Kantor::where('is_active', true)->orderBy('nama')->get();
        $roles   = RoleUser::cases();

        return view('admin.users.index', compact('users', 'kantor', 'roles'));
    }

    public function create(): View
    {
        $kantor  = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatan = Jabatan::aktif()->get();
        $roles    = RoleUser::cases();

        return view('admin.users.create', compact('kantor', 'jabatan', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:150', 'regex:/^[A-Za-z\s\.\,\-\']+$/'],
            'nik'        => ['required', 'string', 'regex:/^AP\d{9}$/', 'unique:users,nik'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', Password::min(8)->letters()->numbers()],
            'kantor_id'  => ['required', 'exists:kantors,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => ['nullable', 'string', 'max:100'],
            'roles'      => ['required', 'array', 'min:1'],
            'roles.*'    => ['string', 'exists:roles,name'],
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
            'email_verified' => true, // Admin buat manual = langsung verified
        ]);

        $roleIds = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->attach($roleIds->mapWithKeys(
            fn($id) =>
            [$id => ['assigned_at' => now()]]
        )->all());

        AuditService::log(
            AksiAudit::USER_ROLE_ASSIGNED,
            auth()->id(),
            $user,
            null,
            ['roles' => $request->roles]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil dibuat.");
    }

    public function show(User $user): View
    {
        $user->load('kantor', 'jabatan', 'roles');

        $recentPermohonan = $user->permohonan()
            ->with('kantor')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'recentPermohonan'));
    }

    public function edit(User $user): View
    {
        $user->load('roles');
        $kantor  = Kantor::where('is_active', true)->orderBy('nama')->get();
        $jabatan = Jabatan::aktif()->get();
        $roles    = RoleUser::cases();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'kantor', 'jabatan', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'kantor_id'  => ['required', 'exists:kantors,id'],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'jabatan_custom' => ['nullable', 'string', 'max:100'],
            'is_active'  => ['required', 'boolean'],
            'roles'      => ['required', 'array', 'min:1'],
            'roles.*'    => ['string', 'exists:roles,name'],
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
            'jabatan_custom' => $request->jabatan_custom
                ? strtoupper(trim($request->jabatan_custom))
                : null,
            'is_active'      => $request->boolean('is_active'),
        ]);

        // Sync roles
        $roleIds = Role::whereIn('name', $request->roles)->pluck('id');
        $user->roles()->sync($roleIds->mapWithKeys(
            fn($id) =>
            [$id => ['assigned_at' => now()]]
        )->all());

        AuditService::log(
            AksiAudit::USER_ROLE_ASSIGNED,
            auth()->id(),
            $user,
            $before,
            ['roles' => $request->roles, 'is_active' => $request->boolean('is_active')]
        );

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        AuditService::auth(AksiAudit::USER_PASSWORD_RESET, auth()->id(), [
            'target_user_id' => $user->id,
        ]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }
}
