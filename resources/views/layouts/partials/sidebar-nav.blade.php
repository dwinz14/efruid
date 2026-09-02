@php
    use App\Enums\RoleUser;
    $user = auth()->user();
    $currentRoute = request()->route()->getName();

    // Helper: active class
    $active = fn(string $pattern) => str_starts_with($currentRoute ?? '', $pattern)
        ? 'bg-brand-800 text-white'
        : 'text-brand-300 hover:bg-brand-800 hover:text-white';
@endphp

{{-- Dashboard --}}
<a href="{{ route('dashboard') }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('dashboard') }}">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

{{-- Permohonan (semua user) --}}
<a href="{{ route('permohonan.index') }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('permohonan') }}">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Permohonan Saya
</a>

{{-- Approval (Atasan, Dirut, IT Staff) --}}
@php
    $hasPendingAsAtasan = \App\Models\Permohonan::where('atasan_id', $user->id)
        ->where('status', \App\Enums\StatusPermohonan::PENDING_ATASAN->value)
        ->exists();
@endphp
@if ($user->hasAnyRole([RoleUser::DIRUT, RoleUser::IT_STAFF, RoleUser::SUPER_ADMIN]) || $hasPendingAsAtasan)

    <div class="pt-3 pb-1 px-3">
        <p class="text-brand-500 text-xs uppercase font-semibold tracking-wider">Approval</p>
    </div>

    @if ($hasPendingAsAtasan || $user->hasAnyRole([RoleUser::SUPER_ADMIN]))
        <a href="{{ route('approval.atasan.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('approval.atasan') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Approval Atasan
        </a>
    @endif

    @if ($user->hasAnyRole([RoleUser::DIRUT, RoleUser::SUPER_ADMIN]))
        <a href="{{ route('approval.dirut.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('approval.dirut') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Approval Direktur
        </a>
    @endif

    @if ($user->hasAnyRole([RoleUser::IT_STAFF, RoleUser::SUPER_ADMIN]))
        <a href="{{ route('eksekusi.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('eksekusi') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Eksekusi IT
        </a>
    @endif
@endif

{{-- Super Admin --}}
@if ($user->isSuperAdmin())
    <div class="pt-3 pb-1 px-3">
        <p class="text-brand-500 text-xs uppercase font-semibold tracking-wider">Administrasi</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('admin.users') }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        Kelola User
    </a>
    <a href="{{ route('admin.kantor.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('admin.kantors') }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        Kelola Kantor
    </a>
    <a href="{{ route('admin.jabatan.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('admin.jabatans') }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Kelola Jabatan
    </a>
    <a href="{{ route('admin.permohonan.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium
          transition-colors {{ $active('admin.permohonan') }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2
                 -2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012
                 -2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        Semua Permohonan
    </a>
    <a href="{{ route('admin.audit-logs.index') }}"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('admin.audit-logs') }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Audit Log
    </a>
@endif

{{-- Profil (semua user) --}}
<div class="pt-3 pb-1 px-3">
    <p class="text-brand-500 text-xs uppercase font-semibold tracking-wider">Akun</p>
</div>
<a href="{{ route('profile.edit') }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active('profile') }}">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Profil Saya
</a>
