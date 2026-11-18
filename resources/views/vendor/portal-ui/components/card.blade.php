@props([
    'padding' => true,
    'headerClass' => 'p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center rounded-t-lg',
    'headerStyle' => null,
    'stickyHeader' => false,
    'stickyTop' => '0px',
    'stickyZIndex' => 20,
    'bodyClass' => null,
    'footerClass' => 'p-4 border-t border-gray-100 bg-gray-50',
])

@php
    $hasPadding = filter_var($padding, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $hasPadding = $hasPadding ?? ! empty($padding);
    $hasStickyHeader = filter_var($stickyHeader, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $hasStickyHeader = $hasStickyHeader ?? ! empty($stickyHeader);
    $computedBodyClass = $bodyClass ?: ($hasPadding ? 'p-4' : '');
    $computedHeaderClass = $hasStickyHeader ? $headerClass.' sticky' : $headerClass;
    $computedHeaderStyle = trim(implode(' ', array_filter([
        $hasStickyHeader ? 'top: '.$stickyTop.';' : null,
        $hasStickyHeader ? 'z-index: '.$stickyZIndex.';' : null,
        $headerStyle,
    ])));
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm mb-4']) }}>
    @isset($header)
        <div class="{{ $computedHeaderClass }}" @if($computedHeaderStyle) style="{{ $computedHeaderStyle }}" @endif>
            {{ $header }}
        </div>
    @endisset

    <div class="{{ $computedBodyClass }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="{{ $footerClass }}">
            {{ $footer }}
        </div>
    @endisset
</div>
