@props(['href' => null])

@php
    $classes = 'inline-flex justify-center items-center gap-2 px-5 py-3 bg-gradient-to-r from-cyan-300 via-fuchsia-400 to-violet-500 border border-transparent rounded-2xl font-black text-xs text-slate-950 uppercase tracking-widest hover:shadow-cyan-400/30 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-slate-950 transition-all ease-in-out duration-300 shadow-lg shadow-fuchsia-500/25 transform hover:-translate-y-0.5';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
