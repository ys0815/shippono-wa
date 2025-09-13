@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-active'
            : 'nav-inactive';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
