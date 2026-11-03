@props(['description' => null, 'back' => null, 'backLabel' => 'Kembali'])

@if ($description || $back || isset($actions))
<div {{ $attributes->class('flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between') }}>
    <div class="min-w-0 space-y-1">
        @if ($back)
        <a href="{{ $back }}"
            class="inline-flex items-center gap-1 rounded text-sm font-medium text-slate-600 hover:text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                    clip-rule="evenodd" />
            </svg>
            {{ $backLabel }}
        </a>
        @endif

        @if ($description)
        <p class="text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
    <div class="flex flex-shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
@endif