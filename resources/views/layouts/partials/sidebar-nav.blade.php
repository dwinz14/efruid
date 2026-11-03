@php
$user = auth()->user();

// Cek menu aktif: halaman detail (.show) ikut menyalakan menu induknya.
$is = fn(string ...$patterns) => request()->routeIs(...$patterns);

$isAtasan = $user->isAtasan();
$isDirut = $user->isDirut();
$isIt = $user->isItStaff();

// Jika user memegang dua role persetujuan sekaligus, label dibedakan.
$bothApprover = $isAtasan && $isDirut;

$icons = [
'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
'permohonan' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0
01.293.707V19a2 2 0 01-2 2z',
'approval' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2
0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
'riwayat' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
'eksekusi' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37
2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37
2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0
00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0
001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
'semua' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0
012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018
0z',
'sessions' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002
2z',
'security' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0
5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
'audit' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0
01.293.707V19a2 2 0 01-2 2z',
'kantor' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1
0 011-1h2a1 1 0 011 1v5m-4 0h4',
'jabatan' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2
0 012-2h2a2 2 0 012 2',
];
@endphp

{{-- Aksi utama --}}
<a href="{{ route('permohonan.create') }}"
    class="mb-3 flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-3 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
    Buat Permohonan
</a>

{{-- Menu utama (semua user) --}}
<div class="space-y-1">
    <x-sidebar-link :href="route('dashboard')" :icon="$icons['dashboard']" :active="$is('dashboard*')">
        Dashboard
    </x-sidebar-link>

    <x-sidebar-link :href="route('permohonan.index')" :icon="$icons['permohonan']" :active="$is('permohonan.*')"
        :badge="$navCounts['permohonan']">
        Permohonan Saya
    </x-sidebar-link>
</div>

{{-- Tugas Saya (Atasan, Direktur, IT) --}}
@if ($isAtasan || $isDirut || $isIt)
<x-sidebar-section label="Tugas Saya">
    @if ($isAtasan)
    <x-sidebar-link :href="route('approval.atasan.index')" :icon="$icons['approval']"
        :active="$is('approval.atasan.index', 'approval.atasan.show')" :badge="$navCounts['atasan']">
        {{ $bothApprover ? 'Persetujuan Atasan' : 'Persetujuan' }}
    </x-sidebar-link>
    <x-sidebar-link :href="route('approval.atasan.riwayat')" :icon="$icons['riwayat']"
        :active="$is('approval.atasan.riwayat')">
        {{ $bothApprover ? 'Riwayat Atasan' : 'Riwayat Persetujuan' }}
    </x-sidebar-link>
    @endif

    @if ($isDirut)
    <x-sidebar-link :href="route('approval.dirut.index')" :icon="$icons['approval']"
        :active="$is('approval.dirut.index', 'approval.dirut.show')" :badge="$navCounts['dirut']">
        {{ $bothApprover ? 'Persetujuan Direktur' : 'Persetujuan' }}
    </x-sidebar-link>
    <x-sidebar-link :href="route('approval.dirut.riwayat')" :icon="$icons['riwayat']"
        :active="$is('approval.dirut.riwayat')">
        {{ $bothApprover ? 'Riwayat Direktur' : 'Riwayat Persetujuan' }}
    </x-sidebar-link>
    @endif

    @if ($isIt)
    <x-sidebar-link :href="route('eksekusi.index')" :icon="$icons['eksekusi']"
        :active="$is('eksekusi.index', 'eksekusi.show')" :badge="$navCounts['eksekusi']">
        Antrean Eksekusi
    </x-sidebar-link>
    <x-sidebar-link :href="route('eksekusi.riwayat')" :icon="$icons['riwayat']" :active="$is('eksekusi.riwayat')">
        Riwayat Eksekusi
    </x-sidebar-link>
    @endif
</x-sidebar-section>
@endif

{{-- Super Admin --}}
@if ($user->isSuperAdmin())
<x-sidebar-section label="Administrasi">
    <x-sidebar-link :href="route('admin.permohonan.index')" :icon="$icons['semua']" :active="$is('admin.permohonan.*')">
        Semua Permohonan
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.users.index')" :icon="$icons['users']" :active="$is('admin.users.*')"
        :badge="$navCounts['users']">
        Kelola User
    </x-sidebar-link>
</x-sidebar-section>

<x-sidebar-section label="Pemantauan">
    <x-sidebar-link :href="route('admin.sessions.index')" :icon="$icons['sessions']" :active="$is('admin.sessions.*')">
        Sesi Aktif
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.security.index')" :icon="$icons['security']" :active="$is('admin.security.*')">
        Keamanan
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.audit-logs.index')" :icon="$icons['audit']" :active="$is('admin.audit-logs.*')">
        Audit Log
    </x-sidebar-link>
</x-sidebar-section>

<x-sidebar-section label="Data Master">
    <x-sidebar-link :href="route('admin.kantor.index')" :icon="$icons['kantor']" :active="$is('admin.kantor.*')">
        Kantor
    </x-sidebar-link>
    <x-sidebar-link :href="route('admin.jabatan.index')" :icon="$icons['jabatan']" :active="$is('admin.jabatan.*')">
        Jabatan
    </x-sidebar-link>
</x-sidebar-section>
@endif