@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'iconBgClass' => 'bg-portal-gradient',
    'iconClass' => 'text-white',
    'wrapperClass' => 'p-6 border-b border-gray-50 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start gap-4',
    'actionsClass' => 'flex items-center gap-3',
])

<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <div class="min-w-0">
        <h2 class="text-base font-semibold text-gray-900 m-0 flex items-center">
            @if($icon)
                <span class="w-9 h-9 rounded-lg {{ $iconBgClass }} {{ $iconClass }} flex items-center justify-center mr-3 shadow-sm">
                    <i class="fa {{ $icon }} text-sm" aria-hidden="true"></i>
                </span>
            @endif
            <span>{{ $title }}</span>
            @isset($titleExtra)
                {{ $titleExtra }}
            @endisset
        </h2>
        @if($subtitle)
            <p class="text-sm text-gray-500 mt-2 mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="{{ $actionsClass }}">
            {{ $actions }}
        </div>
    @endisset
</div>
