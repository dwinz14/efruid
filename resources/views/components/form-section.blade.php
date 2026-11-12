@props(['title', 'description' => null])

@php($headingId = 'sec-' . \Illuminate\Support\Str::slug($title))

<section aria-labelledby="{{ $headingId }}" {{ $attributes->class('card overflow-hidden') }}>
    <div class="flex items-start justify-between gap-4 border-b border-surface-border px-5 py-4 sm:px-6">
        <div class="min-w-0">
            <h3 id="{{ $headingId }}" class="text-base font-semibold text-slate-900">{{ $title }}</h3>
            @if ($description)
                <p class="mt-0.5 text-sm text-slate-600">{{ $description }}</p>
            @endif
        </div>

        @isset($aside)
            <div class="flex-shrink-0">{{ $aside }}</div>
        @endisset
    </div>

    <div class="p-5 sm:p-6">{{ $slot }}</div>
</section>
