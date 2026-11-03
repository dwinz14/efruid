{{-- Flash messages terpusat: success, info, warning, error (session & error bag 'error'). --}}
@php
// Key error yang sudah ditampilkan inline di dekat field-nya.
$inlineKeys = ['otp', 'email', 'password', 'current_password', 'alasan_reject'];
$hasInlineError = collect($inlineKeys)->contains(fn ($key) => $errors->has($key));

$alerts = collect([
['type' => 'success', 'title' => null, 'message' => session('success')],
['type' => 'info', 'title' => null, 'message' => session('info')],
['type' => 'warning', 'title' => 'Perhatian', 'message' => session('warning')],
[
'type' => 'danger',
'title' => 'Terjadi kesalahan',
'message' => session('error') ?? $errors->first('error'),
],
[
'type' => 'warning',
'title' => null,
'message' => $errors->any() && ! $errors->has('error') && ! $hasInlineError
? 'Terdapat kesalahan pada form. Periksa kembali isian Anda.'
: null,
],
])->filter(fn ($alert) => filled($alert['message']));

$styles = [
'success' => [
'box' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
'icon' => 'text-emerald-500',
'path' => 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
1.414l2 2a1 1 0 001.414 0l4-4z',
],
'info' => [
'box' => 'border-brand-200 bg-brand-50 text-brand-800',
'icon' => 'text-brand-500',
'path' => 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0
100-2v-3a1 1 0 00-1-1H9z',
],
'warning' => [
'box' => 'border-amber-200 bg-amber-50 text-amber-800',
'icon' => 'text-amber-500',
'path' => 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53
0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z',
],
'danger' => [
'box' => 'border-red-200 bg-red-50 text-red-800',
'icon' => 'text-red-500',
'path' => 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414
1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z',
],
];
@endphp

@if ($alerts->isNotEmpty())
<div class="space-y-3">
    @foreach ($alerts as $alert)
    @php $s = $styles[$alert['type']]; @endphp
    <div x-data="{ open: true }" x-show="open" role="{{ $alert['type'] === 'danger' ? 'alert' : 'status' }}"
        class="flex items-start gap-3 rounded-xl border p-4 text-sm {{ $s['box'] }}">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 {{ $s['icon'] }}" fill="currentColor" viewBox="0 0 20 20"
            aria-hidden="true">
            <path fill-rule="evenodd" d="{{ $s['path'] }}" clip-rule="evenodd" />
        </svg>
        <div class="min-w-0 flex-1">
            @if ($alert['title'])
            <p class="font-semibold">{{ $alert['title'] }}</p>
            @endif
            <p class="{{ $alert['title'] ? 'mt-0.5' : '' }}">{{ $alert['message'] }}</p>
        </div>
        <button type="button" @click="open = false" aria-label="Tutup pesan"
            class="flex-shrink-0 rounded p-0.5 opacity-60 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-current">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    @endforeach
</div>
@endif