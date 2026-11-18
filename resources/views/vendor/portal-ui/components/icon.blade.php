@props([
    'name',
    'family' => 'solid',
    'label' => null,
])

@php
    $styleClasses = [
        'solid' => 'fa-solid',
        'regular' => 'fa-regular',
        'brands' => 'fa-brands',
    ];
    $hasExplicitStyle = preg_match('/(^|\s)(fa-solid|fa-regular|fa-brands|fas|far|fab)(\s|$)/', $name) === 1;
    $iconClasses = trim(($hasExplicitStyle ? '' : ($styleClasses[$family] ?? $styleClasses['solid']).' ').$name);
@endphp

<i
    {{ $attributes->class($iconClasses) }}
    @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
></i>
