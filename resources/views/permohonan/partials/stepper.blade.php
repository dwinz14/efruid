@php
    $steps = [
        1 => ['label' => 'Jenis Form',       'desc' => 'Pilih tipe permohonan'],
        2 => ['label' => 'Isi Data',          'desc' => 'Lengkapi informasi'],
        3 => ['label' => 'Preview & Submit',  'desc' => 'Tinjau & kirim'],
    ];
@endphp

<div class="relative">
    {{-- Background card stepper --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-5">
        <div class="flex items-center">
            @foreach ($steps as $num => $info)
                @php
                    $isActive = $num === $step;
                    $isDone   = $num < $step;
                    $isLast   = $num === count($steps);
                @endphp

                {{-- Step item --}}
                <div class="flex items-center {{ $isLast ? '' : 'flex-1' }}">

                    {{-- Circle + Label --}}
                    <div class="flex items-center gap-3 flex-shrink-0">

                        {{-- Circle --}}
                        <div class="relative flex-shrink-0">
                            @if ($isActive)
                                {{-- Active: pulse ring --}}
                                <span class="absolute inset-0 rounded-full bg-brand-500/20 animate-ping"
                                      style="animation-duration: 2s;"></span>
                            @endif
                            <div class="relative w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                                {{ $isDone   ? 'bg-brand-600 text-white shadow-md shadow-brand-500/30' : '' }}
                                {{ $isActive ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/40 ring-4 ring-brand-100' : '' }}
                                {{ !$isDone && !$isActive ? 'bg-slate-100 text-slate-400 border-2 border-slate-200' : '' }}">
                                @if ($isDone)
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    {{ $num }}
                                @endif
                            </div>
                        </div>

                        {{-- Label & Desc --}}
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold leading-none
                                {{ $isActive ? 'text-brand-700' : ($isDone ? 'text-slate-600' : 'text-slate-400') }}">
                                {{ $info['label'] }}
                            </p>
                            <p class="text-xs mt-0.5
                                {{ $isActive ? 'text-brand-500' : ($isDone ? 'text-slate-400' : 'text-slate-300') }}">
                                {{ $info['desc'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Connector line --}}
                    @if (!$isLast)
                        <div class="flex-1 mx-4 h-0.5 rounded-full overflow-hidden bg-slate-100">
                            <div class="h-full rounded-full transition-all duration-500
                                {{ $isDone ? 'bg-gradient-to-r from-brand-500 to-brand-400 w-full' : 'w-0' }}">
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Mobile: step label --}}
        <div class="sm:hidden mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-brand-700">
                {{ $steps[$step]['label'] }}
            </p>
            <p class="text-xs text-slate-400">
                Langkah {{ $step }} dari {{ count($steps) }}
            </p>
        </div>
    </div>

    {{-- Progress bar thin (below card) --}}
    <div class="mt-2 h-0.5 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-brand-600 to-brand-400 rounded-full transition-all duration-700"
             style="width: {{ round((($step - 1) / (count($steps) - 1)) * 100) }}%"></div>
    </div>
</div>
