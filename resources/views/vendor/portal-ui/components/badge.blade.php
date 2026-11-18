@props([
    'variant' => 'primary',
    'icon' => null,
])

@php
    $baseClasses = 'px-2.5 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm border';
    $variants = [
        'primary' => 'bg-portal/10 text-portal dark:bg-portal-dark/20 dark:text-portal-secondary',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        'danger'  => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        'info'    => 'bg-blue-100 text-blue-700 border-blue-200',
        'secondary' => 'bg-gray-100 text-gray-700 border-gray-200',
    ];

    // Se uma variante é explicitamente fornecida, use-a; caso contrário, use a primeira disponível ou 'primary'.
    $activeVariant = $variant && isset($variants[$variant]) ? $variant : 'primary';
    $variantClasses = $variants[$activeVariant];
@endphp

<span {{ $attributes->merge(['class' => $baseClasses.' '.$variantClasses]) }}>
    @if($icon)
        <i class="fa {{ $icon }}" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</span>

