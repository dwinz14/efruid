<div {{ $attributes->class('sticky bottom-3 z-20 mt-6') }}>
    <div
        class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-surface-border bg-white p-3 shadow-card">
        <div>
            @isset($back)
                {{ $back }}
            @endisset
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2">{{ $slot }}</div>
    </div>
</div>
