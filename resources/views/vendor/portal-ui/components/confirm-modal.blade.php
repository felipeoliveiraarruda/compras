@props([
    'title' => 'Confirmar ação',
    'message' => null,
    'confirmLabel' => 'Confirmar',
    'cancelLabel' => 'Cancelar',
    'confirmVariant' => 'danger',
    'confirmIcon' => null,
    'confirmAction' => null,
    'cancelAction' => null,
    'maxWidth' => 'md',
])

<x-portal::modal
    :title="$title"
    :maxWidth="$maxWidth"
    {{ $attributes }}
>
    <div class="space-y-4">
        @if($message)
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $message }}</p>
        @else
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ $slot }}
            </div>
        @endif

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-portal::button
                type="button"
                variant="outline"
                :click="$cancelAction"
            >
                {{ $cancelLabel }}
            </x-portal::button>

            <x-portal::button
                type="button"
                :variant="$confirmVariant"
                :icon="$confirmIcon"
                :click="$confirmAction"
            >
                {{ $confirmLabel }}
            </x-portal::button>
        </div>
    </div>
</x-portal::modal>
