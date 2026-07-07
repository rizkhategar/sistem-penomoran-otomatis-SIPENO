@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl text-white bg-blue-700 shadow-sm transition duration-200 ease-out'
            : 'inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition duration-200 ease-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
