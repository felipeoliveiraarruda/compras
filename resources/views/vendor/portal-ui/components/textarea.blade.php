@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'rows' => 4,
    'help' => null,
    'error' => null,
    'errorKey' => null,
    'prepend' => null,
    'required' => false,
    'wrapperClass' => 'mb-4',
    'labelClass' => 'block text-sm font-medium text-gray-700 mb-1',
    'fieldClass' => '',
])

@php
    $attributeBag = $attributes->getAttributes();
    $wireModelAttribute = collect(array_keys($attributeBag))->first(fn ($key) => strncmp($key, 'wire:model', 10) === 0);
    $wireModel = $wireModelAttribute ? $attributeBag[$wireModelAttribute] : null;
    $candidateErrorKeys = array_values(array_filter([$errorKey, $name, $wireModel], fn ($key) => filled($key)));
    $fieldId = $id ?: ($name ?: ($wireModel ? str_replace(['.', '[', ']'], ['-', '-', ''], $wireModel) : null));
    $resolvedError = $error;

    if (empty($resolvedError) && isset($errors)) {
        foreach ($candidateErrorKeys as $candidateErrorKey) {
            if ($errors->has($candidateErrorKey)) {
                $resolvedError = $errors->first($candidateErrorKey);
                break;
            }
        }
    }
    $hasWireModel = ! empty($wireModel);
    $hasError = ! empty($resolvedError);
    $currentValue = $hasWireModel ? $value : ($name ? old($name, $value) : $value);
    $baseFieldClass = 'form-textarea w-full px-3 py-2 border-2 focus:ring-4 transition-all outline-none';
    $shapeClass = $prepend ? 'rounded-r-lg border-l-0' : 'rounded-lg';
    $stateClass = $hasError ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-500/10' : 'border-gray-200 focus:border-portal focus:ring-portal/10';
    $addonStateClass = $hasError ? 'border-red-300 bg-red-50 text-red-500' : 'border-gray-200 bg-gray-50 text-gray-500';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label @if($fieldId) for="{{ $fieldId }}" @endif class="{{ $labelClass }}">
            {{ $label }}@if($required) <span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative flex items-stretch w-full">
        @if($prepend)
            <span class="inline-flex items-center px-3 rounded-l-lg border-2 border-r-0 text-sm {{ $addonStateClass }}">
                {{ $prepend }}
            </span>
        @endif

        <textarea
            @if($fieldId) id="{{ $fieldId }}" @endif
            @if($name) name="{{ $name }}" @endif
            rows="{{ $rows }}"
            class="{{ trim($baseFieldClass.' '.$shapeClass.' '.$stateClass.' '.$fieldClass) }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($hasError) aria-invalid="true" @endif
            {{ $attributes->merge([
                'class' => '
                    bg-white dark:bg-gray-700/50
                    text-gray-900 dark:text-gray-100
                    border-gray-300 dark:border-gray-600
                    focus:border-portal focus:ring-portal
                    rounded-md shadow-sm
                    transition duration-150 ease-in-out
                '
            ]) }}
        >{{ $currentValue }}</textarea>
    </div>

    @if($help)
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif
    @if($hasError)
        <p class="text-xs text-red-500 mt-1 flex items-center gap-1">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            {{ $resolvedError }}
        </p>
    @endif
</div>
