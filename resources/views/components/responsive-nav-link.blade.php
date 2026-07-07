@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 text-start text-sm font-medium text-white bg-white/15 rounded-lg transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2 text-start text-sm font-medium text-blue-100 hover:text-white hover:bg-white/10 rounded-lg transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
