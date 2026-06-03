@props(['color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-slate-100 text-slate-700',
        'red' => 'bg-red-50 text-red-700',
        'yellow' => 'bg-amber-50 text-amber-700',
        'green' => 'bg-emerald-50 text-emerald-700',
        'blue' => 'bg-blue-50 text-blue-700',
        'indigo' => 'bg-indigo-50 text-indigo-700',
        'purple' => 'bg-violet-50 text-violet-700',
        'pink' => 'bg-pink-50 text-pink-700',
        'orange' => 'bg-orange-50 text-orange-700',
    ];

    $colorClass = $colors[$color] ?? $colors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {$colorClass}"]) }}>
    {{ $slot }}
</span>