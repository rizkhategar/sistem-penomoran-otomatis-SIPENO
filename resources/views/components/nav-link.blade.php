@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-2xl text-white bg-white/18 shadow-sm ring-1 ring-white/20 transition duration-200 ease-out'
            : 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-2xl text-slate-200/90 hover:text-white hover:bg-white/12 hover:ring-1 hover:ring-white/15 transition duration-200 ease-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
