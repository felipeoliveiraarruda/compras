@props([
    'label' => 'Item',
    'route' => null,
    'url' => null,
    'icon' => 'fa-circle',
    'active' => null,
    'can' => null,
    'guest' => null,
    'external' => null,
    'children' => [],
    'href' => null,
    'isActive' => null,
    'isExternal' => null,
    'target' => null,
    'rel' => null,
])

@php
    $normalizedItem = \SistemasEel\PortalUi\Support\Navigation::item([
        'label' => $label,
        'route' => $route,
        'url' => $url,
        'icon' => $icon,
        'active' => $active,
        'can' => $can,
        'guest' => $guest,
        'external' => $external,
        'children' => $children,
        'href' => $href,
        'is_active' => $isActive,
        'is_external' => $isExternal,
        'target' => $target,
        'rel' => $rel,
    ]);
    $isVisible = $normalizedItem !== null;
    $children = $normalizedItem['children'] ?? [];
    $hasChildren = count($children) > 0;
    $href = $href ?: ($normalizedItem['href'] ?? '#');
    $target = $target ?: ($normalizedItem['target'] ?? null);
    $rel = $rel ?: ($normalizedItem['rel'] ?? null);
    $isExternal = $isExternal ?? ($normalizedItem['is_external'] ?? false);
    $isActive = $isActive ?? ($normalizedItem['is_active'] ?? false);
    $submenuId = 'portal-sidebar-submenu-'.md5($label.$href.serialize($children));
    $baseClass = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 dark:text-gray-300 hover:bg-white/10 dark:hover:bg-white/10 transition-all group';
    $activeClass = $isActive ? ' bg-white/15 dark:bg-white/20 text-white dark:text-gray-100 font-medium' : '';
@endphp

@if($isVisible)
    <div data-portal-sidebar-item>
        @if($hasChildren)
            <button
                type="button"
                {{ $attributes->merge(['class' => $baseClass.$activeClass.' w-full']) }}
                data-portal-nav-item
                data-portal-submenu-toggle
                aria-expanded="{{ $isActive ? 'true' : 'false' }}"
                aria-controls="{{ $submenuId }}"
            >
                <i class="fa {{ $icon }} w-5 text-center group-hover:scale-110 transition-transform flex-shrink-0 text-white/70 dark:text-gray-400" aria-hidden="true"></i>
                <span class="whitespace-nowrap truncate flex-1 text-left" data-portal-hide-collapsed>{{ $label }}</span>
                <i class="fa fa-chevron-down text-xs text-white/60 transition-transform {{ $isActive ? 'rotate-180' : '' }}" data-portal-submenu-icon data-portal-hide-collapsed aria-hidden="true"></i>
                <span class="hidden absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-800 text-white dark:text-gray-100 text-xs rounded whitespace-nowrap z-50" data-portal-tooltip>
                    {{ $label }}
                </span>
            </button>

            <div
                id="{{ $submenuId }}"
                class="{{ $isActive ? '' : 'hidden' }} mt-1 space-y-1 pl-4"
                data-portal-submenu
                data-portal-hide-collapsed
            >
                @foreach($children as $child)
                    <x-portal::sidebar-item
                        :label="$child['label'] ?? 'Item'"
                        :route="$child['route'] ?? null"
                        :url="$child['url'] ?? null"
                        :icon="$child['icon'] ?? 'fa-circle'"
                        :active="$child['active'] ?? null"
                        :can="$child['can'] ?? null"
                        :guest="$child['guest'] ?? null"
                        :external="$child['external'] ?? null"
                        :children="$child['children'] ?? []"
                        :href="$child['href'] ?? null"
                        :is-active="$child['is_active'] ?? null"
                        :is-external="$child['is_external'] ?? null"
                        :target="$child['target'] ?? null"
                        :rel="$child['rel'] ?? null"
                        class="text-sm"
                    />
                @endforeach
            </div>
        @else
            <a
                href="{{ $href }}"
                {{ $attributes->merge(['class' => $baseClass.$activeClass]) }}
                data-portal-nav-item
                @if($target) target="{{ $target }}" @endif
                @if($rel) rel="{{ $rel }}" @endif
                @if($isActive) aria-current="page" @endif
            >
                <i class="fa {{ $icon }} w-5 text-center group-hover:scale-110 transition-transform flex-shrink-0 text-white/70 dark:text-gray-400" aria-hidden="true"></i>
                <span class="whitespace-nowrap truncate flex-1" data-portal-hide-collapsed>{{ $label }}</span>
                @if($isExternal)
                    <i class="fa fa-up-right-from-square text-[10px] text-white/50" data-portal-hide-collapsed aria-hidden="true"></i>
                @endif
                <span class="hidden absolute left-full ml-2 px-2 py-1 bg-gray-900 dark:bg-gray-800 text-white dark:text-gray-100 text-xs rounded whitespace-nowrap z-50" data-portal-tooltip>
                    {{ $label }}
                </span>
            </a>
        @endif
    </div>
@endif
