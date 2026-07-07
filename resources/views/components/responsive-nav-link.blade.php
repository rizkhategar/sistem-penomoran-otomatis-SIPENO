@props(['active'])

<a {{ $attributes->merge(['class' => ($active ?? false)
    ? 'block w-full px-4 py-3 text-sm font-semibold text-white bg-blue-700 rounded-xl transition duration-200'
    : 'block w-full px-4 py-3 text-sm font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition duration-200'
]) }}>
    {{ $slot }}
</a>
