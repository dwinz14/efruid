{{-- Breadcrumb. $items = [['label' => 'Dashboard', 'url' => '/'], ['label' => 'Halaman ini']]
     Item terakhir = halaman aktif (tanpa tautan). Di layar < sm hanya item terakhir yang tampil. --}}
@props(['items' => []])

@php($lastIndex = count($items) - 1)

@if ($lastIndex >= 0)
    <nav aria-label="Breadcrumb" {{ $attributes->class('min-w-0') }}>
        <ol class="flex min-w-0 items-center gap-2 text-sm">
            @foreach ($items as $i => $item)
                @if ($i < $lastIndex)
                    <li class="hidden items-center gap-2 sm:flex">
                        @if (!empty($item['url']))
                            <a href="{{ $item['url'] }}"
                                class="rounded font-medium text-slate-600 transition-colors hover:text-brand-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">{{ $item['label'] }}</a>
                        @else
                            <span class="font-medium text-slate-600">{{ $item['label'] }}</span>
                        @endif
                        <svg class="h-4 w-4 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </li>
                @else
                    <li class="flex min-w-0 items-center">
                        <span aria-current="page"
                            class="truncate font-semibold text-brand-950">{{ $item['label'] }}</span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
