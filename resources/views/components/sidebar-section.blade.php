@props(['label'])

<div class="pt-5" role="group" aria-label="{{ $label }}">
    <p class="px-4 pb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-300" aria-hidden="true">
        {{ $label }}
    </p>
    <div class="space-y-1">{{ $slot }}</div>
</div>
