@php
    $steps = [1 => 'Jenis Form', 2 => 'Isi Data', 3 => 'Periksa & Kirim'];
    $total = count($steps);
@endphp

<nav aria-label="Progres pembuatan permohonan" class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm sm:px-5">
    {{-- Mobile --}}
    <div class="sm:hidden">
        <p class="flex items-center justify-between gap-3 text-sm text-slate-600">
            <span><span class="font-semibold text-slate-900">Langkah {{ $step }}/{{ $total }}</span> &middot;
                {{ $steps[$step] }}</span>
            <span class="text-xs font-medium text-brand-700">{{ round(($step / $total) * 100) }}%</span>
        </p>
        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200" aria-hidden="true">
            <div class="h-full rounded-full bg-brand-600" style="width: {{ round(($step / $total) * 100) }}%"></div>
        </div>
    </div>

    {{-- Desktop --}}
    <ol class="hidden items-center sm:flex">
        @foreach ($steps as $num => $label)
            @php
                $done = $num < $step;
                $active = $num === $step;
            @endphp
            <li class="flex items-center {{ $num < $total ? 'flex-1' : '' }}"
                @if ($active) aria-current="step" @endif>
                <span class="flex items-center gap-2.5">
                    <span
                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-sm font-semibold transition-colors
                            {{ $done ? 'bg-brand-600 text-white' : ($active ? 'border-2 border-brand-600 bg-brand-50 text-brand-700' : 'border-2 border-slate-200 bg-white text-slate-500') }}">
                        @if ($done)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="3" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span
                        class="text-sm {{ $active ? 'font-semibold text-slate-900' : ($done ? 'font-medium text-slate-700' : 'text-slate-500') }}">
                        {{ $label }}
                        <span class="sr-only">
                            ({{ $done ? 'selesai' : ($active ? 'langkah saat ini' : 'belum dikerjakan') }})
                        </span>
                    </span>
                </span>

                @if ($num < $total)
                    <span class="mx-3 h-px flex-1 {{ $done ? 'bg-brand-600' : 'bg-slate-200' }}"
                        aria-hidden="true"></span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
