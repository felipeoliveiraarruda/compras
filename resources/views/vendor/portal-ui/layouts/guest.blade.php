@php
    $brand = config('portal-ui.brand', []);
    $navigation = config('portal-ui.navigation', []);
    $layout = config('portal-ui.layout', []);
    $brandName = $brand['name'] ?? config('app.name', 'Sistema');
    $brandSubtitle = $brand['subtitle'] ?? '';
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
<body class="{{ $bodyClass }}" data-portal-ui="guest">
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
                    <div class="mx-auto w-full max-w-3xl">
                        <div class="mb-6 text-center">
                            @if(! empty($brand['logo']))
                                <img
                                    src="{{ asset($brand['logo']) }}"
                                    alt="{{ $brand['logo_alt'] ?? $brandName }}"
                                    class="mx-auto mb-4 max-h-16 object-contain dark:brightness-90 dark:contrast-110"
                                >
                            @else
                                <div class="mx-auto mb-4 h-14 w-14 rounded-2xl bg-portal-gradient text-white flex items-center justify-center shadow-lg">
                                    <span class="text-xl font-bold">{{ mb_strtoupper(mb_substr($brandName, 0, 1)) }}</span>
                                </div>
                            @endif

                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $brandName }}</h1>
                            @if($brandSubtitle !== '')
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $brandSubtitle }}</p>
                            @endif
                        </div>

                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                @isset($slot)
                                    {{ $slot }}
                                @else
                                    @yield('content')
                                @endisset
                            </div>
                        </div>
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
