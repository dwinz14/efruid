@php
    $steps = [
        1 => [
            'label' => 'Jenis Form',
            'desc' => 'Format Penugasan',
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ],
        2 => [
            'label' => 'Isi Data',
            'desc' => 'Formulir & Akses',
            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ],
        3 => [
            'label' => 'Periksa & Kirim',
            'desc' => 'Pratinjau Dokumen',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
    $total = count($steps);
    $percentage = round(($step / $total) * 100);
@endphp

<nav aria-label="Progres pembuatan permohonan" class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:px-6 sm:py-4">
    {{-- Mobile Version --}}
    <div class="sm:hidden">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-brand-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                    Langkah {{ $step }} dari {{ $total }}
                </span>
                <p class="mt-0.5 truncate text-base font-bold text-slate-900">
                    {{ $steps[$step]['label'] }}
                    <span class="text-xs font-normal text-slate-500">· {{ $steps[$step]['desc'] }}</span>
                </p>
            </div>
            <span class="inline-flex flex-shrink-0 items-center rounded-full bg-brand-50 px-2.5 py-1 text-xs font-bold text-brand-700 border border-brand-200">
                {{ $percentage }}%
            </span>
        </div>
        
        {{-- Visual multi-segment progress bar --}}
        <div class="mt-3 grid grid-cols-3 gap-1.5" aria-hidden="true">
            @for ($i = 1; $i <= $total; $i++)
                <div class="h-1.5 rounded-full transition-all duration-300 {{ $i <= $step ? 'bg-brand-600' : 'bg-slate-200' }}"></div>
            @endfor
        </div>
    </div>

    {{-- Desktop Version --}}
    <ol class="hidden items-center sm:flex">
        @foreach ($steps as $num => $item)
            @php
                $done = $num < $step;
                $active = $num === $step;
            @endphp
            <li class="relative flex items-center {{ $num < $total ? 'flex-1' : '' }}"
                @if ($active) aria-current="step" @endif>
                <div class="group flex items-center gap-3">
                    {{-- Indicator Circle --}}
                    <span
                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-sm font-bold transition-all duration-200
                            {{ $done 
                                ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/20' 
                                : ($active 
                                    ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30 ring-4 ring-brand-100' 
                                    : 'border-2 border-slate-200 bg-slate-50 text-slate-400') }}">
                        @if ($done)
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @elseif ($active)
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                        @else
                            <span>{{ $num }}</span>
                        @endif
                    </span>

                    {{-- Step Titles --}}
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider {{ $active ? 'text-brand-600' : ($done ? 'text-emerald-700' : 'text-slate-400') }}">
                            Langkah 0{{ $num }}
                        </p>
                        <p class="text-sm {{ $active ? 'font-bold text-slate-900' : ($done ? 'font-semibold text-slate-800' : 'font-medium text-slate-400') }}">
                            {{ $item['label'] }}
                        </p>
                        <p class="text-xs {{ $active ? 'text-slate-600' : 'text-slate-400' }}">
                            {{ $item['desc'] }}
                        </p>
                        <span class="sr-only">
                            ({{ $done ? 'selesai' : ($active ? 'langkah saat ini' : 'belum dikerjakan') }})
                        </span>
                    </div>
                </div>

                {{-- Connector Line --}}
                @if ($num < $total)
                    <div class="mx-4 h-0.5 flex-1 rounded-full {{ $done ? 'bg-emerald-600' : 'bg-slate-200' }}"
                        aria-hidden="true"></div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
