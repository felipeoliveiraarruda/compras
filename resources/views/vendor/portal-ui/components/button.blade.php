@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'name' => null,
    'value' => null,
    'href' => null,
    'click' => null,
    'onclick' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'full' => false,
    'disabled' => false,
])

@php
    $hasIconOnlyContent = ! $slot->isNotEmpty();
    $isFullWidth = filter_var($full, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $isFullWidth = $isFullWidth ?? ! empty($full);
    $isDisabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $isDisabled = $isDisabled ?? ! empty($disabled);
    $baseClasses = 'inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2';
    $variants = [
        'primary' => 'bg-portal-gradient text-white hover:brightness-110 shadow-sm focus:ring-portal/30',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-300',
        'outline' => 'bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm focus:ring-gray-300',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
        'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100 focus:ring-gray-300',
    ];
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];
    $variantClasses = isset($variants[$variant]) ? $variants[$variant] : $variants['primary'];
    $sizeClasses = isset($sizes[$size]) ? $sizes[$size] : $sizes['md'];
    $iconOnlySizes = [
        'sm' => 'h-9 w-9 p-0 text-xs',
        'md' => 'h-10 w-10 p-0 text-sm',
        'lg' => 'h-11 w-11 p-0 text-base',
    ];
    $sizeClasses = $hasIconOnlyContent ? ($iconOnlySizes[$size] ?? $iconOnlySizes['md']) : $sizeClasses;
    $stateClasses = $isDisabled ? ' opacity-50 cursor-not-allowed pointer-events-none' : '';
    $widthClasses = $isFullWidth ? ' w-full' : '';
    $classes = $baseClasses.' '.$variantClasses.' '.$sizeClasses.$stateClasses.$widthClasses;
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) aria-disabled="true" @endif
        @if($click) wire:click="{{ $click }}" @endif
        @if($onclick) onclick="{{ $onclick }}" @endif
    >
        @if($icon && $iconPosition === 'left')
            <x-portal::icon :name="$icon" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <x-portal::icon :name="$icon" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        @if($name) name="{{ $name }}" @endif
        @if(! is_null($value)) value="{{ $value }}" @endif
        {{ $attributes->merge(['class' => $classes]) }}
        @if($isDisabled) disabled @endif
        @if($click) wire:click="{{ $click }}" @endif
        @if($onclick) onclick="{{ $onclick }}" @endif
    >
        @if($icon && $iconPosition === 'left')
            <x-portal::icon :name="$icon" />
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <x-portal::icon :name="$icon" />
        @endif
    </button>
@endif
