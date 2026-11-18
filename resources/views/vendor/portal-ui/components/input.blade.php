@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'error' => null,
    'errorKey' => null,
    'prepend' => null,
    'append' => null,
    'required' => false,
    'wrapperClass' => 'mb-4',
    'labelClass' => 'block text-sm font-medium text-gray-700 mb-1',
    'fieldClass' => '',
])

@php
    $attributeBag = $attributes->getAttributes();
    $wireModelAttribute = collect(array_keys($attributeBag))->first(fn ($key) => strncmp($key, 'wire:model', 10) === 0);
    $wireModel = $wireModelAttribute ? $attributeBag[$wireModelAttribute] : null;
    $attributeClass = trim((string) $attributes->get('class', ''));
    $inputAttributes = $attributes->except('class');
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
    $hasAddon = ! empty($prepend) || ! empty($append);
    $inputValue = $type === 'password'
        ? null
        : ($hasWireModel && is_null($value) ? null : ($name ? old($name, $value) : $value));
    $baseFieldClass = 'form-input w-full px-3 py-2 border-2 focus:ring-4 outline-none';
    $sharedFieldClass = 'text-gray-900 dark:text-gray-100 placeholder:text-gray-400 transition duration-150 ease-in-out';
    $shapeClass = $prepend ? 'rounded-r-lg border-l-0' : ($append ? 'rounded-l-lg border-r-0' : 'rounded-lg');
    $stateClass = $hasError
        ? 'border-red-300 bg-red-50 dark:border-red-500/60 dark:bg-red-950/30 focus:border-red-500 focus:ring-red-500/10'
        : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/50 focus:border-portal focus:ring-portal/10';
    $addonStateClass = $hasError
        ? 'border-red-300 bg-red-50 text-red-500 dark:border-red-500/60 dark:bg-red-950/30 dark:text-red-300'
        : 'border-gray-300 bg-gray-50 text-gray-500 dark:border-gray-600 dark:bg-gray-800/60 dark:text-gray-400';
    $groupClass = $hasError
        ? 'border-red-300 bg-red-50 dark:border-red-500/60 dark:bg-red-950/30 focus-within:border-red-500 focus-within:ring-4 focus-within:ring-red-500/10'
        : 'border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700/50 focus-within:border-portal focus-within:ring-4 focus-within:ring-portal/10';
    $addonInputClass = trim('form-input min-w-0 flex-1 px-3 py-2 border-0 outline-none bg-transparent shadow-none focus:ring-0 '.$sharedFieldClass.' '.$fieldClass.' '.$attributeClass);
    $inputClass = trim($baseFieldClass.' '.$shapeClass.' '.$stateClass.' rounded-md shadow-sm '.$sharedFieldClass.' '.$fieldClass.' '.$attributeClass);
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label @if($fieldId) for="{{ $fieldId }}" @endif class="{{ $labelClass }}">
            {{ $label }}@if($required) <span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative flex items-stretch w-full">
        @if($hasAddon)
            <div class="flex w-full items-stretch rounded-lg border-2 overflow-hidden transition-all {{ $groupClass }}">
                @if($prepend)
                    <span class="inline-flex items-center px-3 border-r text-sm {{ $addonStateClass }}">
                        {{ $prepend }}
                    </span>
                @endif

                <input
                    @if($fieldId) id="{{ $fieldId }}" @endif
                    @if($name) name="{{ $name }}" @endif
                    type="{{ $type }}"
                    class="{{ $addonInputClass }}"
                    style="background-color: transparent; border-color: transparent; box-shadow: none;"
                    @if(! is_null($inputValue)) value="{{ $inputValue }}" @endif
                    @if($placeholder) placeholder="{{ $placeholder }}" @endif
                    @if($required) required @endif
                    @if($hasError) aria-invalid="true" @endif
                    {{ $inputAttributes }}
                >

                @if($append)
                    <span class="inline-flex items-center px-3 border-l text-sm {{ $addonStateClass }}">
                        {{ $append }}
                    </span>
                @endif
            </div>
        @else
            <input
                @if($fieldId) id="{{ $fieldId }}" @endif
                @if($name) name="{{ $name }}" @endif
                type="{{ $type }}"
                class="{{ $inputClass }}"
                @if(! is_null($inputValue)) value="{{ $inputValue }}" @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($required) required @endif
                @if($hasError) aria-invalid="true" @endif
                {{ $inputAttributes }}
            >
        @endif
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
