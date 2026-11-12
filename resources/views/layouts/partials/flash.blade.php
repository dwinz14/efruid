{{-- Flash messages terpusat: success, info, warning, error (session & error bag 'error'). --}}
@php
    // Key error yang sudah ditampilkan inline di dekat field-nya.
    $inlineKeys = ['otp', 'email', 'password', 'current_password', 'alasan_reject'];
    $hasInlineError = collect($inlineKeys)->contains(fn($key) => $errors->has($key));

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
            'message' =>
                $errors->any() && !$errors->has('error') && !$hasInlineError
                    ? 'Terdapat kesalahan pada form. Periksa kembali isian Anda.'
                    : null,
        ],
    ])->filter(fn($alert) => filled($alert['message']));
@endphp

@if ($alerts->isNotEmpty())
    <div class="space-y-3">
        @foreach ($alerts as $alert)
            <x-alert :type="$alert['type']" :title="$alert['title']" dismissible>{{ $alert['message'] }}</x-alert>
        @endforeach
    </div>
@endif
