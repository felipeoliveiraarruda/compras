@php
    $brand = config('portal-ui.brand', []);
    $navigation = config('portal-ui.navigation', []);
    $layout = config('portal-ui.layout', []);
    $brandName = $brand['name'] ?? config('app.name', 'Sistema');
    $pageTitle = $title ?? trim($__env->yieldContent('title', ''));
    $documentTitle = $pageTitle !== '' ? $pageTitle.' - '.$brandName : $brandName;
    $bodyClass = trim('h-full bg-gray-50 dark:bg-gray-900 '.($layout['body_class'] ?? ''));
    $visibleNavigationGroups = \SistemasEel\PortalUi\Support\Navigation::groups($navigation['groups'] ?? []);
    $hasSidebar = isset($sidebar) || $__env->hasSection('sidebar') || count($visibleNavigationGroups) > 0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    @include('portal-ui::layouts.partials.head', ['documentTitle' => $documentTitle])
</head>
<body class="{{ $bodyClass }}" data-portal-ui="app">
    <div
        class="min-h-full portal-ui-layout"
        data-portal-layout
        data-portal-has-sidebar="{{ $hasSidebar ? 'true' : 'false' }}"
    >
        @if($hasSidebar)
            <div
                class="hidden fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
                data-portal-overlay
                data-portal-sidebar-close
            ></div>
        @endif

        @if($hasSidebar)
            @include('portal-ui::layouts.partials.sidebar', ['visibleNavigationGroups' => $visibleNavigationGroups])
        @endif

        <div
            class="transition-all duration-300 ease-in-out"
            data-portal-content
        >
            @include('portal-ui::layouts.partials.topbar', ['hasSidebar' => $hasSidebar])

            <main class="pt-2 lg:pt-6 pb-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Adicionar classes dark para o container de conteúdo -->
                    <div class="text-gray-900 dark:text-gray-100">
                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('portal-ui-before-scripts')
    @include('portal-ui::layouts.partials.scripts')
    @stack('scripts')
    @stack('portal-ui-after-scripts')
    @yield('modals')
</body>
</html>
