@props([
    'badge' => null,
    'badgeIcon' => null,
    'title' => '',
    'subtitle' => null,
    'icon' => null,
])

<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#1A2B4C] via-[#1E3A5F] to-[#243B66] border border-[#243B66] p-7 sm:p-9 text-white shadow-2xl">
    <!-- Destellos ambientales decorativos -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#33FF57]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#00BCD4]/15 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-3">
            @if($badge)
                <div>
                    <span class="text-[#33FF57] text-xs font-black uppercase tracking-widest flex items-center gap-2">
                        @if($badgeIcon) <i class="{{ $badgeIcon }}"></i> @endif
                        <span>{{ $badge }}</span>
                    </span>
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight flex items-center gap-3">
                @if($icon) <i class="{{ $icon }} text-[#33FF57]"></i> @endif
                <span>{!! $title !!}</span>
            </h1>

            @if($subtitle)
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-normal leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if(isset($action))
            <div class="flex items-center gap-3 self-start lg:self-center">
                {{ $action }}
            </div>
        @endif
    </div>
</div>
