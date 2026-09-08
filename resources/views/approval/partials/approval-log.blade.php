<div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
    <div class="px-5 sm:px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Riwayat Alur Persetujuan (Audit Trail)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Catatan jejak rekam permohonan ini</p>
            </div>
        </div>
        <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full">
            {{ $logs->count() }} Aktivitas
        </span>
    </div>

    <div class="p-5 sm:p-6">
        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
            @foreach ($logs as $log)
                @php
                    $isApprove = $log->aksi === 'approved';
                    $isReject = $log->aksi === 'rejected';
                    $dotBg = $isApprove ? 'bg-emerald-500 ring-emerald-100 text-white' : ($isReject ? 'bg-red-500 ring-red-100 text-white' : 'bg-brand-500 ring-brand-100 text-white');
                @endphp
                <div class="relative group">
                    {{-- Timeline Marker Dot --}}
                    <div class="absolute -left-[30px] top-0.5 w-5 h-5 rounded-full {{ $dotBg }} ring-4 flex items-center justify-center shadow-xs">
                        @if ($isApprove)
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @elseif ($isReject)
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        @endif
                    </div>

                    {{-- Content Box --}}
                    <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-200/60 group-hover:bg-slate-50 group-hover:border-slate-300/80 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-800">{{ $log->user?->name ?? 'Sistem' }}</span>
                                @if ($log->user?->jabatan_label)
                                    <span class="text-xs text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">
                                        {{ $log->user->jabatan_label }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-medium text-slate-400">
                                {{ $log->created_at->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB
                            </span>
                        </div>

                        <div class="mt-2 flex items-center gap-2 text-xs">
                            <span class="font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $isApprove ? 'bg-emerald-100 text-emerald-700' : ($isReject ? 'bg-red-100 text-red-700' : 'bg-brand-100 text-brand-700') }}">
                                {{ $log->aksi }}
                            </span>
                            <span class="text-slate-500 font-mono">
                                {{ $log->status_dari }} &rarr; <span class="font-bold text-slate-700">{{ $log->status_ke }}</span>
                            </span>
                        </div>

                        @if ($log->catatan)
                            <div class="mt-2.5 p-3 rounded-lg bg-white border border-slate-200/80 text-xs text-slate-600 italic leading-relaxed">
                                <span class="font-semibold text-slate-400 not-italic block mb-0.5 text-[10px] uppercase tracking-wider">Catatan:</span>
                                "{{ $log->catatan }}"
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
