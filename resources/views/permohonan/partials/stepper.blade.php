@php $steps = ['Jenis Form', 'Isi Data', 'Preview & Submit']; @endphp

<div class="flex items-center gap-0">
    @foreach ($steps as $i => $label)
        @php
            $num = $i + 1;
            $isActive = $num === $step;
            $isDone = $num < $step;
        @endphp
        <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
            <div class="flex items-center gap-2 stepper-step">
                <div
                    class="{{ $isActive ? 'stepper-circle-active' : ($isDone ? 'stepper-circle-done' : 'stepper-circle-inactive') }}">
                    @if ($isDone)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span
                    class="text-sm {{ $isActive ? 'text-brand-700 font-semibold' : ($isDone ? 'text-brand-600' : 'text-slate-400') }} hidden sm:block">
                    {{ $label }}
                </span>
            </div>
            @if (!$loop->last)
                <div class="flex-1 h-px mx-3 {{ $isDone ? 'bg-brand-400' : 'bg-slate-200' }}"></div>
            @endif
        </div>
    @endforeach
</div>
