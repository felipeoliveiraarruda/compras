@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'inlineLabel' => null,
    'help' => null,
    'error' => null,
    'errorKey' => null,
    'checked' => false,
    'disabled' => false,
    'overrideClass' => null,
])

@php
    $attributeBag = $attributes->getAttributes();
    $wireModelAttribute = collect(array_keys($attributeBag))->first(fn ($key) => strncmp($key, 'wire:model', 10) === 0);
    $wireModel = $wireModelAttribute ? $attributeBag[$wireModelAttribute] : null;
    $candidateErrorKeys = array_values(array_filter([$errorKey, $name, $wireModel], fn ($key) => filled($key)));
    $id = $id ?? $name ?? ($wireModel ? str_replace(['.', '[', ']'], ['-', '-', ''], $wireModel) : uniqid('switch-'));
    $resolvedError = $error;

    if (empty($resolvedError) && isset($errors)) {
        foreach ($candidateErrorKeys as $candidateErrorKey) {
            if ($errors->has($candidateErrorKey)) {
                $resolvedError = $errors->first($candidateErrorKey);
                break;
            }
        }
    }
    $hasError = ! empty($resolvedError);

    $defaultSwitchClass = "relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-portal/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-portal";
    $computedSwitchClass = $overrideClass ?: $defaultSwitchClass;
@endphp

<div {{ $attributes->whereStartsWith('class')->merge(['class' => '']) }}>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-2" for="{{ $id }}">{!! $label !!}</label>
    @endif
    <label class="inline-flex items-center cursor-pointer {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
        <input
            type="checkbox"
            class="sr-only peer"
            id="{{ $id }}"
            name="{{ $name }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->whereDoesntStartWith('class') }}
        />
        <div class="{{ $computedSwitchClass }}"></div>
        @if($inlineLabel)
            <span class="ml-3 text-sm font-medium text-gray-700">{!! $inlineLabel !!}</span>
        @endif
    </label>
    @if ($help)
        <p id="{{ $id }}Help" class="text-xs text-gray-500 mt-1">{!! $help !!}</p>
    @endif
    @if($hasError)
        <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><i class="fa fa-exclamation-circle"></i> {!! $resolvedError !!}</p>
    @endif
</div>
