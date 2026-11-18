@props([
    'variant' => 'info',
    'title' => null,
    'icon' => null,
    'dismissible' => false,
    'closeLabel' => 'Fechar',
])

@php
    $variantClasses = [
        'success' => 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
        'danger'  => 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
        'info'    => 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
    ];
    $iconClasses = [
        'success' => 'text-green-500 dark:text-green-400 fa-check-circle',
        'danger' => 'text-red-500 dark:text-red-400 fa-exclamation-circle',
        'warning' => 'text-yellow-500 dark:text-yellow-400 fa-exclamation-triangle',
        'info' => 'text-blue-500 dark:text-blue-400 fa-info-circle',
    ];
    $classes = isset($variantClasses[$variant]) ? $variantClasses[$variant] : $variantClasses['info'];
    $computedIcon = $icon ?: (isset($iconClasses[$variant]) ? $iconClasses[$variant] : $iconClasses['info']);
@endphp

<div
    @if($dismissible) data-portal-dismissible @endif
    {{ $attributes->merge(['class' => 'relative p-4 border rounded-lg shadow-sm '.$classes]) }}
    role="alert"
>
    <div class="flex items-start">
        <div class="flex-shrink-0 mt-0.5">
            <i class="fa {{ $computedIcon }} text-lg" aria-hidden="true"></i>
        </div>
        <div class="ml-3 flex-1 text-left">
            @if($title)
                <h3 class="text-sm font-semibold mb-1">{{ $title }}</h3>
            @endif
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button
                    type="button"
                    data-portal-dismiss
                    class="inline-flex cursor-pointer rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 hover:bg-black/5 transition-colors"
                    aria-label="{{ $closeLabel }}"
                >
                    <span class="sr-only">{{ $closeLabel }}</span>
                    <i class="fa fa-times opacity-50 hover:opacity-100" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</div>
