@props([
    'only' => null,
    'mode' => 'icon',
    'viewClick' => null,
    'editClick' => null,
    'deleteClick' => null,
    'viewHref' => null,
    'editHref' => null,
    'deleteHref' => null,
    'viewOnclick' => null,
    'editOnclick' => null,
    'deleteOnclick' => null,
    'viewLabel' => 'Ver',
    'editLabel' => 'Editar',
    'deleteLabel' => 'Excluir',
    'viewTitle' => 'Ver',
    'editTitle' => 'Editar',
    'deleteTitle' => 'Excluir',
    'viewVariant' => 'ghost',
    'editVariant' => 'outline',
    'deleteVariant' => 'danger',
    'viewIcon' => 'fa-eye',
    'editIcon' => 'fa-pen',
    'deleteIcon' => 'fa-trash',
    'size' => 'sm',
    'align' => 'right',
])

@php
    $normalizedOnly = is_array($only)
        ? array_values(array_filter($only))
        : array_values(array_filter(array_map('trim', explode(',', (string) $only))));

    $showView = empty($normalizedOnly) || in_array('view', $normalizedOnly, true);
    $showEdit = empty($normalizedOnly) || in_array('edit', $normalizedOnly, true);
    $showDelete = empty($normalizedOnly) || in_array('delete', $normalizedOnly, true);
    $showLabels = in_array($mode, ['label', 'inline'], true);
    $iconMode = ! $showLabels;
    $iconButtonClass = $iconMode ? 'shadow-sm' : '';
    $viewVariant = $iconMode ? 'outline' : $viewVariant;
    $editVariant = $iconMode ? 'outline' : $editVariant;
    $deleteVariant = $iconMode ? 'outline' : $deleteVariant;
    $viewButtonClass = $iconMode ? 'text-gray-600 hover:text-gray-800' : '';
    $editButtonClass = $iconMode ? 'border-blue-200 text-blue-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700' : '';
    $deleteButtonClass = $iconMode ? 'border-red-200 text-red-600 hover:border-red-300 hover:bg-red-50 hover:text-red-700 focus:ring-red-200' : '';
@endphp

<x-portal::table-actions :align="$align" {{ $attributes }}>
    @if($showView && ($viewClick || $viewHref || $viewOnclick))
        <x-portal::button
            :size="$size"
            :variant="$viewVariant"
            :icon="$viewIcon"
            :href="$viewHref"
            :click="$viewClick"
            :onclick="$viewOnclick"
            title="{{ $viewTitle }}"
            class="{{ trim($iconButtonClass.' '.$viewButtonClass) }}"
        >
            @if($showLabels)
                {{ $viewLabel }}
            @endif
        </x-portal::button>
    @endif

    @if($showEdit && ($editClick || $editHref || $editOnclick))
        <x-portal::button
            :size="$size"
            :variant="$editVariant"
            :icon="$editIcon"
            :href="$editHref"
            :click="$editClick"
            :onclick="$editOnclick"
            title="{{ $editTitle }}"
            class="{{ trim($iconButtonClass.' '.$editButtonClass) }}"
        >
            @if($showLabels)
                {{ $editLabel }}
            @endif
        </x-portal::button>
    @endif

    @if($showDelete && ($deleteClick || $deleteHref || $deleteOnclick))
        <x-portal::button
            :size="$size"
            :variant="$deleteVariant"
            :icon="$deleteIcon"
            :href="$deleteHref"
            :click="$deleteClick"
            :onclick="$deleteOnclick"
            title="{{ $deleteTitle }}"
            class="{{ trim($iconButtonClass.' '.$deleteButtonClass) }}"
        >
            @if($showLabels)
                {{ $deleteLabel }}
            @endif
        </x-portal::button>
    @endif
</x-portal::table-actions>
