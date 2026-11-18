@props([
    'title' => '',
    'icon' => null,
    'maxWidth' => '2xl',
    'id' => null,
    'show' => true,
    'closeLabel' => 'Fechar',
    'headerClass' => 'bg-portal-gradient text-white',
])

@php
    $attributeBag = $attributes->getAttributes();
    $wireModelAttribute = collect(array_keys($attributeBag))->first(fn ($key) => strncmp($key, 'wire:model', 10) === 0);
    $wireModel = $wireModelAttribute ? $attributeBag[$wireModelAttribute] : null;
    $isWireModal = ! empty($wireModel);
    $shouldRender = $isWireModal || $show;
    $isInitiallyHidden = $isWireModal || preg_match('/(?:^|\s)is-hidden(?:\s|$)/', (string) $attributes->get('class', '')) === 1;
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full',
    ];
    $maxWidthClass = isset($widths[$maxWidth]) ? $widths[$maxWidth] : $widths['2xl'];
@endphp

@if($shouldRender)
    <div
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->merge(['class' => 'fixed inset-0 z-50 overflow-y-auto']) }}
        data-portal-modal
        @if($isWireModal) data-portal-modal-wire @endif
        role="dialog"
        aria-modal="true"
        aria-hidden="{{ $isInitiallyHidden ? 'true' : 'false' }}"
        @if($title) aria-label="{{ $title }}" @endif
        @if($isWireModal)
            wire:ignore.self
            x-data="{ show: $wire.entangle('{{ $wireModel }}') }"
            x-show="show"
            x-bind:aria-hidden="show ? 'false' : 'true'"
            x-cloak
            x-init="
                $watch('show', value => {
                    if (! value) return

                    requestAnimationFrame(() => {
                        $el.querySelector('[data-autofocus]')?.focus()
                    })
                })
            "
            style="display: none;"
            x-on:keydown.escape.window="show = false"
        @endif
    >
        <div
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            aria-hidden="true"
            @if($isWireModal)
                @click="show = false"
                @keydown.escape.window="show = false"
            @else
                data-portal-modal-close
            @endif
        ></div>

        <div
            class="relative min-h-full flex items-start justify-center p-0 sm:p-4"
            @if(! $isWireModal) data-portal-modal-surface @endif
            @if($isWireModal)
                @click.self="show = false"
                @keydown.escape.window="show = false"
            @endif
        >
            <div
                data-portal-modal-panel
                class="bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl {{ $maxWidthClass }} w-full mt-20 sm:my-10 relative overflow-visible"
            >
                <div class="{{ $headerClass }} rounded-t-3xl sm:rounded-t-2xl px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        @if($icon)
                            <x-portal::icon :name="$icon" />
                        @endif
                        {{ $title }}
                    </h2>

                    @isset($close)
                        {{ $close }}
                    @else
                        <button
                            type="button"
                            class="cursor-pointer text-white hover:bg-white/20 rounded-lg p-2 transition-all"
                            aria-label="{{ $closeLabel }}"
                            @if($isWireModal)
                                @click="show = false"
                            @else
                                data-portal-modal-close
                            @endif
                        >
                            <x-portal::icon name="fa-times" />
                        </button>
                    @endisset
                </div>

                <div class="p-4 sm:p-6">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="rounded-b-3xl sm:rounded-b-2xl px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-2">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endif
