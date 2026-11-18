@props([
    'id' => null,
])

@php
    $flashConfig = (array) config('portal-ui.flash', []);

    $buckets = [
        'success' => $flashConfig['success_keys'] ?? ['success', 'message'],
        'error' => $flashConfig['error_keys'] ?? ['error', 'danger'],
        'warning' => $flashConfig['warning_keys'] ?? ['warning'],
        'info' => $flashConfig['info_keys'] ?? ['info', 'status'],
    ];

    $persistentKeys = (array) ($flashConfig['persistent_keys'] ?? []);
    $titleKeys = (array) ($flashConfig['title_keys'] ?? []);
    $idKeys = (array) ($flashConfig['id_keys'] ?? []);

    $defaultTitles = [
        'success' => 'Sucesso',
        'error' => 'Erro',
        'warning' => 'Atenção',
        'info' => 'Informação',
    ];

    $collected = [];

    foreach ($buckets as $variant => $keys) {
        foreach ((array) $keys as $key) {
            $value = session($key);
            if ($value === null || $value === '') {
                continue;
            }

            $messageIdKey = $idKeys[$variant][$key] ?? ($key.'_id');
            $messageId = session($messageIdKey);

            if ($id !== null && (string) $messageId !== (string) $id) {
                continue;
            }

            $collected[] = [
                'variant' => $variant,
                'key' => $key,
                'message' => $value,
                'id' => $messageId,
            ];
        }
    }

    $isPersistent = function (string $variant) use ($persistentKeys): bool {
        foreach ((array) ($persistentKeys[$variant] ?? []) as $key) {
            if (session($key)) {
                return true;
            }
        }
        return false;
    };

    $customTitle = function (string $variant) use ($titleKeys) {
        foreach ((array) ($titleKeys[$variant] ?? []) as $key) {
            $value = session($key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        return null;
    };
@endphp

@if(!empty($collected))
    <div data-portal-flash-messages {{ $attributes }}>
        @foreach($collected as $flash)
            @php
                $variant = $flash['variant'];
                $resolvedTitle = $customTitle($variant) ?? ($defaultTitles[$variant] ?? null);
                $persistent = $isPersistent($variant);
            @endphp
            <x-portal::flash-alert
                :variant="$variant"
                :title="$resolvedTitle"
                :auto-dismiss="! $persistent"
            >
                {{ $flash['message'] }}
            </x-portal::flash-alert>
        @endforeach
    </div>
@endif
