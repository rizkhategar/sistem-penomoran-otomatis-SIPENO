@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-white bg-white/15 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-blue-100 hover:text-white hover:bg-white/10 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
