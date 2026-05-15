@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-full border border-cyan-300/40 bg-cyan-300/10 px-4 py-2 text-sm font-bold leading-5 text-cyan-100 shadow-lg shadow-cyan-500/10 focus:outline-none focus:ring-2 focus:ring-cyan-300/50 transition duration-200 ease-in-out'
            : 'inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-sm font-semibold leading-5 text-slate-300 hover:border-fuchsia-300/30 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-fuchsia-300/40 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
