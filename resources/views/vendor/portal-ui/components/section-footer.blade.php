@props([
    'align' => 'between',
    'bordered' => true,
    'muted' => false,
])

@php
    $hasBorder = filter_var($bordered, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $hasBorder = $hasBorder ?? ! empty($bordered);
    $hasMutedText = filter_var($muted, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $hasMutedText = $hasMutedText ?? ! empty($muted);

    $alignments = [
        'start' => 'justify-start',
        'center' => 'justify-center',
        'end' => 'justify-end',
        'between' => 'justify-between',
    ];

    $alignmentClass = $alignments[$align] ?? $alignments['between'];
    $borderClass = $hasBorder ? 'border-t border-gray-200 dark:border-gray-700' : '';
    $textClass = $hasMutedText ? 'text-sm text-gray-500 dark:text-gray-400' : '';
    $classes = trim('flex flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center '.$alignmentClass.' '.$borderClass.' '.$textClass);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
