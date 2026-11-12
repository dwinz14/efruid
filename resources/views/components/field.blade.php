@props(['name', 'label', 'required' => false, 'optional' => false, 'hint' => null])

<div {{ $attributes->class('min-w-0') }}>
    <label for="{{ $name }}" class="label {{ $required ? 'label-required' : '' }}">
        {{ $label }}
        @if ($optional)
            <span class="font-normal text-slate-600">(opsional)</span>
        @endif
    </label>

    {{ $slot }}

    @if ($hint)
        <p id="{{ $name }}-hint" class="mt-1 text-xs text-slate-600">{{ $hint }}</p>
    @endif

    @error($name)
        <p id="{{ $name }}-error" class="field-error">{{ $message }}</p>
    @enderror
</div>
