@props([
    'title' => '',
    'value' => '',
    'subtitle' => null,
    'icon' => 'fas fa-chart-bar',
    'color' => 'emerald', // emerald, sky, teal, amber, violet, green
])

@php
    $badgeColors = [
        'emerald' => 'bg-emerald-50 text-[#2E6F40]',
        'green'   => 'bg-emerald-50 text-[#2E6F40]',
        'sky'     => 'bg-sky-50 text-[#00BCD4]',
        'teal'    => 'bg-teal-50 text-[#00BCD4]',
        'amber'   => 'bg-amber-50 text-amber-600',
        'violet'  => 'bg-purple-50 text-purple-600',
        'navy'    => 'bg-slate-100 text-[#1A2B4C]',
    ];
    $iconBadge = $badgeColors[$color] ?? $badgeColors['emerald'];
@endphp

<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
    <!-- Top Row: Título y Badge Circular con Ícono -->
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-500 tracking-tight">{{ $title }}</span>
        <div class="w-8 h-8 rounded-full {{ $iconBadge }} flex items-center justify-center text-xs flex-shrink-0 group-hover:scale-105 transition-transform">
            <i class="{{ $icon }}"></i>
        </div>
    </div>

    <!-- Valor Principal Numérico -->
    <div class="mt-3">
        <h3 class="text-2xl sm:text-3xl font-extrabold text-[#343A40] tracking-tight">{{ $value }}</h3>
        @if($subtitle || isset($footer))
            <div class="mt-1 text-xs text-slate-400 font-medium">
                {{ $footer ?? $subtitle }}
            </div>
        @endif
    </div>
</div>

