@props([
    'icon' => 'fa-tachometer-alt',
    'title' => '',
    'subtitle' => '',
    'backRoute' => null,
    'backLabel' => 'Voltar',
    'headerClass' => 'bg-portal-gradient',
])

<div {{ $attributes->merge(['class' => 'relative rounded-2xl shadow-lg overflow-hidden mb-6 ' . $headerClass]) }}>
    <div class="relative z-10 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm shadow-inner shrink-0">
                    <i class="fa {{ $icon }} text-white text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 tracking-tight truncate">
                        {{ $title }}
                    </h1>
                    @if($subtitle)
                        <p class="text-white/80 text-sm sm:text-base font-medium">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{ $actions ?? '' }}

                @if($backRoute)
                    <a href="{{ route($backRoute) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all backdrop-blur-sm border border-white/20 text-sm font-semibold shadow-sm">
                        <i class="fa fa-arrow-left"></i>
                        {{ $backLabel }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Elementos decorativos -->
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10 bg-white -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full opacity-5 bg-white translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
</div>
