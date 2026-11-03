@props(['label'])

<div class="pt-4" role="group" aria-label="{{ $label }}">
    <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-400" aria-hidden="true">{{ $label }}
    </p>
    <div class="space-y-1">{{ $slot }}</div>
</div>