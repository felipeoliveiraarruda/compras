@props([
    'title',
    'message' => null,
    'icon' => 'fa-inbox',
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'px-5 py-8',
        'md' => 'px-6 py-10',
        'lg' => 'px-6 py-12',
    ];
    $paddingClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border-2 border-dashed border-gray-200 text-center dark:border-gray-700 '.$paddingClass]) }}>
    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
        <i class="fa {{ $icon }} text-xl" aria-hidden="true"></i>
    </div>

    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>

    @if($message)
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $message }}</p>
    @endif

    @isset($actions)
        <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
