@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-2xl border border-cyan-300/40 bg-cyan-300/10 px-4 py-3 text-start text-base font-bold text-cyan-100 shadow-lg shadow-cyan-500/10 focus:outline-none focus:ring-2 focus:ring-cyan-300/50 transition duration-200 ease-in-out'
            : 'block w-full rounded-2xl border border-transparent px-4 py-3 text-start text-base font-semibold text-slate-300 hover:border-fuchsia-300/30 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-fuchsia-300/40 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
