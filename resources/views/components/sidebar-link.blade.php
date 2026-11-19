@props(['href', 'icon', 'active' => false, 'badge' => 0])

<a href="{{ $href }}" @if ($active) aria-current="page" @endif
    class="flex min-h-10 items-center gap-3 rounded-full px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300
        {{ $active ? 'bg-white text-brand-900 shadow-sm' : 'text-brand-200 hover:bg-white/10 hover:text-white' }}">
    <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
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
