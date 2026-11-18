@props([
    'align' => 'right',
])

@php
    $alignments = [
        'left' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
    ];

    $alignmentClass = $alignments[$align] ?? $alignments['right'];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex gap-2 '.$alignmentClass]) }}>
    {{ $slot }}
</div>
