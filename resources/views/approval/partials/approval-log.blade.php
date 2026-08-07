<div class="card">
    <div class="card-header">
        <h3 class="text-sm font-semibold text-slate-800">Riwayat Approval</h3>
    </div>
    <div class="card-body">
        <div class="space-y-4">
            @foreach ($logs as $log)
                <div class="flex gap-3">
                    @php
                        $dotClass = match ($log->aksi) {
                            'approved' => 'timeline-dot-done',
                            'rejected' => 'timeline-dot-reject',
                            default => 'timeline-dot-pending',
                        };
                    @endphp
                    <div class="flex flex-col items-center gap-1">
                        <div class="{{ $dotClass }} mt-1.5"></div>
                        @if (!$loop->last)
                            <div class="w-px flex-1 bg-slate-200 min-h-4"></div>
                        @endif
                    </div>
                    <div class="flex-1 pb-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-medium text-slate-800">
                                {{ $log->user?->name ?? 'Sistem' }}
                            </p>
                            <span class="text-xs text-slate-400">
                                {{ $log->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mt-0.5 capitalize">
                            <span class="font-medium">{{ $log->aksi }}</span>:
                            <span class="text-slate-400">{{ $log->status_dari }}</span>
                            &rarr;
                            <span class="text-slate-700">{{ $log->status_ke }}</span>
                        </p>
                        @if ($log->catatan)
                            <p
                                class="text-sm text-slate-500 mt-1 italic bg-slate-50
                                       rounded px-2 py-1">
                                "{{ $log->catatan }}"
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
