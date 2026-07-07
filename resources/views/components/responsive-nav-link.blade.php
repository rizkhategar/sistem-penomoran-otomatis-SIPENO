@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-3 text-start text-sm font-semibold text-white bg-white/18 rounded-2xl ring-1 ring-white/20 transition duration-200 ease-out'
            : 'block w-full px-4 py-3 text-start text-sm font-medium text-slate-200/90 hover:text-white hover:bg-white/12 rounded-2xl transition duration-200 ease-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
