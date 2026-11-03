@props(['href', 'icon', 'active' => false, 'badge' => 0])

<a href="{{ $href }}" @if ($active) aria-current="page" @endif class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400
        {{ $active ? 'bg-brand-800 text-white' : 'text-brand-300 hover:bg-brand-800 hover:text-white' }}">
    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
        aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
    </svg>

    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>

    @if ($badge > 0)
    <span
        class="min-w-5 flex-shrink-0 rounded-full bg-amber-400 px-1.5 py-0.5 text-center text-xs font-semibold leading-none text-slate-900">
        <span aria-hidden="true">{{ $badge > 99 ? '99+' : $badge }}</span>
        <span class="sr-only">{{ $badge }} menunggu tindakan</span>
    </span>
    @endif
</a>