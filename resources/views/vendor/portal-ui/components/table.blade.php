@props([
    'wrapperClass' => null,
    'tableClass' => null,
    'headClass' => 'bg-gray-50 dark:bg-gray-800/50',
    'bodyClass' => 'divide-y divide-gray-100 bg-white dark:divide-gray-700 dark:bg-gray-900/20',
])

@php
    $computedWrapperClass = trim('overflow-x-auto '.($wrapperClass ?? ''));
    $computedTableClass = trim('min-w-full divide-y divide-gray-200 dark:divide-gray-700 '.($tableClass ?? ''));
@endphp

<div {{ $attributes->merge(['class' => $computedWrapperClass]) }}>
    <table class="{{ $computedTableClass }}">
        @isset($head)
            <thead class="{{ $headClass }}">
                {{ $head }}
            </thead>
        @endisset

        <tbody class="{{ $bodyClass }}">
            {{ $body ?? $slot }}
        </tbody>
    </table>
</div>
