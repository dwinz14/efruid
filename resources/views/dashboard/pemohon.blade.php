@php
    use App\Enums\StatusPermohonan;
    $statusConfig = [
        StatusPermohonan::DRAFT->value          => ['label' => 'Draft',            'color' => 'text-slate-500',  'bg' => 'bg-slate-100'],
        StatusPermohonan::PENDING_ATASAN->value  => ['label' => 'Menunggu Atasan',  'color' => 'text-amber-600',  'bg' => 'bg-amber-50'],
        StatusPermohonan::PENDING_DIRUT->value   => ['label' => 'Menunggu Dirut',   'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
        StatusPermohonan::PENDING_IT->value      => ['label' => 'Menunggu IT',      'color' => 'text-brand-600',  'bg' => 'bg-brand-50'],
        StatusPermohonan::EXECUTED->value        => ['label' => 'Selesai',          'color' => 'text-green-600',  'bg' => 'bg-green-50'],
        StatusPermohonan::REJECTED->value        => ['label' => 'Ditolak',          'color' => 'text-red-600',    'bg' => 'bg-red-50'],
        StatusPermohonan::CANCELLED->value       => ['label' => 'Dibatalkan',       'color' => 'text-slate-400',  'bg' => 'bg-slate-50'],
    ];
@endphp

<div class="space-y-5">

    {{-- Greeting --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Selamat datang, {{ $user->name }}
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">
                {{ $user->jabatan_label }} &middot; {{ $user->kantor?->label }}
            </p>
        </div>
        <a href="{{ route('permohonan.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Permohonan
        </a>
    </div>

    {{-- Pending sebagai atasan (jika ada) --}}
    @if($pendingAsAtasan > 0)
        <div class="alert-warning">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75
                         1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743
                         -2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1
                         0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <span class="font-medium">
                    {{ $pendingAsAtasan }} permohonan menunggu persetujuan Anda sebagai atasan.
                </span>
                <a href="{{ route('approval.atasan.index') }}"
                   class="ml-2 underline font-medium text-amber-800">
                    Proses sekarang
                </a>
            </div>
        </div>
    @endif

    {{-- Statistik status --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($statusConfig as $statusVal => $config)
            @php $count = $statuses[$statusVal] ?? 0; @endphp
            <div class="card card-body flex items-center gap-3">
                <div class="w-9 h-9 {{ $config['bg'] }} rounded-lg flex items-center
                            justify-center flex-shrink-0">
                    <span class="text-sm font-bold {{ $config['color'] }}">
                        {{ $count }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-400 leading-tight">
                        {{ $config['label'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Permohonan terbaru --}}
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Permohonan Terbaru</h3>
            <a href="{{ route('permohonan.index') }}"
               class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                Lihat semua
            </a>
        </div>

        @if($recentPermohonan->isEmpty())
            <div class="card-body text-center py-10">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                             a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium text-slate-500">Belum ada permohonan</p>
                <p class="text-xs text-slate-400 mt-1">
                    Klik "Buat Permohonan" untuk memulai
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-auto-style">
                    <thead>
                        <tr>
                            <th>Nomor Dokumen</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPermohonan as $item)
                            <tr>
                                <td>
                                    <span class="font-mono text-xs">
                                        {{ $item->nomor_dokumen ?? '— Draft —' }}
                                    </span>
                                </td>
                                <td class="text-sm">
                                    {{ $item->jenis_permohonan->label() }}
                                </td>
                                <td>
                                    <span class="{{ $item->status->badgeClass() }}">
                                        {{ $item->status->label() }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $item->created_at->locale('id')->isoFormat('D MMM Y') }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('permohonan.show', $item) }}"
                                       class="btn-ghost btn-sm">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>