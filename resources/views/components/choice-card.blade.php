@props(['name', 'value', 'label', 'description' => null, 'checked' => false, 'required' => false, 'model' => null])

<label class="relative block h-full cursor-pointer">
    <input type="radio" name="{{ $name }}" value="{{ $value }}"
        @if ($model) x-model="{{ $model }}" @endif @checked($checked)
        @required($required) {{ $attributes->class('peer sr-only') }}>

    {{-- Kartu --}}
    <div
        class="flex h-full items-start gap-3 rounded-xl border border-slate-300 bg-white p-4 pr-12 transition-colors
            hover:border-slate-400 hover:bg-slate-50
            peer-checked:border-brand-600 peer-checked:bg-brand-50 peer-checked:ring-1 peer-checked:ring-brand-600
            peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500 peer-focus-visible:ring-offset-2">
        <span class="min-w-0">
            <span class="block text-sm font-semibold text-slate-900">{{ $label }}</span>
            @if ($description)
                <span class="mt-0.5 block text-sm text-slate-600">{{ $description }}</span>
            @endif
            {{ $slot }}
        </span>
    </div>

    {{-- Penanda terpilih: saudara dari input agar bisa memakai peer-checked --}}
    <span aria-hidden="true"
        class="pointer-events-none absolute right-4 top-4 flex h-5 w-5 items-center justify-center rounded-full border-2 border-slate-300 bg-white
            after:h-2 after:w-2 after:rounded-full after:bg-white after:opacity-0 after:content-['']
            peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-checked:after:opacity-100"></span>
</label>
