@props(['status', 'size' => 'sm', 'dot' => false])

@php
$status = $status instanceof \App\Enums\StatusPermohonan
? $status
: \App\Enums\StatusPermohonan::from($status);

$sizes = ['sm' => 'px-2.5 py-0.5 text-xs', 'md' => 'px-3 py-1 text-sm'];
@endphp

<span {{ $attributes->class([$status->badgeClass(), $sizes[$size] ?? $sizes['sm']]) }}>
    @if ($dot)
    <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
    @endif
    {{ $status->label() }}
</span>