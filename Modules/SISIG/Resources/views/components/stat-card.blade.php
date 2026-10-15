@props([
    'title' => '',
    'value' => '',
    'subtitle' => null,
    'icon' => 'fas fa-chart-bar',
    'color' => 'emerald', // emerald, sky, teal, amber, violet
])

@php
    $gradients = [
        'emerald' => 'from-emerald-500 to-teal-600',
        'sky' => 'from-sky-500 to-blue-600',
        'teal' => 'from-teal-400 to-emerald-600',
        'amber' => 'from-amber-400 to-orange-500',
        'violet' => 'from-violet-500 to-purple-600',
    ];
    $textColors = [
        'emerald' => 'text-emerald-600',
        'sky' => 'text-sky-600',
        'teal' => 'text-teal-600',
        'amber' => 'text-amber-600',
        'violet' => 'text-violet-600',
    ];
    $barGradient = $gradients[$color] ?? $gradients['emerald'];
    $iconColor = $textColors[$color] ?? $textColors['emerald'];
@endphp

<div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r {{ $barGradient }}"></div>
    <div class="flex items-center justify-between">
        <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">{{ $title }}</span>
        <i class="{{ $icon }} text-2xl {{ $iconColor }} group-hover:scale-110 transition-transform"></i>
    </div>
    <div class="mt-4">
        <h3 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">{{ $value }}</h3>
        @if($subtitle || isset($footer))
            <div class="mt-2.5 flex items-center justify-between text-xs">
                {{ $footer ?? $subtitle }}
            </div>
        @endif
    </div>
</div>
