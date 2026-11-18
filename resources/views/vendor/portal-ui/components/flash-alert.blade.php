@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => true,
    'autoDismiss' => true,
    'dismissDelay' => 5000,
])

@php
    $variantClasses = [
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'error' => 'bg-red-50 text-red-800 border-red-200',
        'warning' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
        'info' => 'bg-blue-50 text-blue-800 border-blue-200',
    ];

    $iconClasses = [
        'success' => 'text-green-600 fa-check-circle',
        'error' => 'text-red-600 fa-exclamation-circle',
        'warning' => 'text-yellow-600 fa-exclamation-triangle',
        'info' => 'text-blue-600 fa-info-circle',
    ];

    $progressColors = [
        'success' => '#22c55e',
        'error' => '#ef4444',
        'warning' => '#eab308',
        'info' => '#3b82f6',
    ];

    $classes = $variantClasses[$variant] ?? $variantClasses['info'];
    $iconClass = $iconClasses[$variant] ?? $iconClasses['info'];
    $progressColor = $progressColors[$variant] ?? $progressColors['info'];
@endphp

<div
    @if($dismissible) data-portal-dismissible @endif
    @if($autoDismiss) data-portal-auto-dismiss="{{ $dismissDelay }}" @endif
    {{ $attributes->merge(['class' => 'relative mb-6 mt-4 border border-l-4 rounded-r-lg p-4 shadow-sm overflow-hidden ' . $classes]) }}
    style="border-left-color: {{ $progressColor }};"
    role="alert"
>
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-2 pr-3">
            <div class="flex-shrink-0">
                <i class="fa {{ $iconClass }} text-lg"></i>
            </div>
            <div class="flex-1 text-left">
                @if($title)
                    <p class="text-sm font-semibold leading-5">{{ $title }}</p>
                    <p class="text-sm mt-0.5 leading-5">{{ $slot }}</p>
                @else
                    <p class="text-sm font-medium leading-5">{{ $slot }}</p>
                @endif
            </div>
        </div>

        @if($dismissible)
            <div class="ml-2 flex-shrink-0 flex">
                <button
                    type="button"
                    data-portal-dismiss
                    class="inline-flex cursor-pointer rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 hover:bg-black/5 transition-colors duration-200"
                >
                    <span class="sr-only">Fechar</span>
                    <i class="fa fa-times opacity-60 hover:opacity-100"></i>
                </button>
            </div>
        @endif
    </div>

    @if($autoDismiss)
        <!-- Barra de Proguesso no final da barra de alerta -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden" style="height: 4px; background-color: rgba(0,0,0,0.05); z-index: 10;">
            <div class="portal-alert-progress" style="height: 100%; background-color: {{ $progressColor }}; width: 100%; transition: width linear;">
            </div>
        </div>
    @endif
</div>
