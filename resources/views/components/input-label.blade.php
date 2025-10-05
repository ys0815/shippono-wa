@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-main-text']) }}>
    {{ $value ?? $slot }}
</label>
