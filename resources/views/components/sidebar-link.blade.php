@props(['active' => false, 'href' => '#', 'color' => 'slate', 'activeClass' => '', 'hoverClass' => '', 'iconColor' => 'text-slate-400', 'activeIconColor' => 'text-slate-600'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition ' . $activeClass
            : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition ' . $hoverClass;
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <span class="{{ ($active ?? false) ? $activeIconColor : $iconColor }} flex-shrink-0">
            {{ $icon }}
        </span>
    @endif
    {{ $slot }}
</a>