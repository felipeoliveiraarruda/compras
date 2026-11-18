@php
    $brand = config('portal-ui.brand', []);
    $navigation = config('portal-ui.navigation', []);
    $groups = $visibleNavigationGroups ?? ($navigation['groups'] ?? []);
    $brandName = $brand['name'] ?? config('app.name', 'Sistema');
    $brandSubtitle = $brand['subtitle'] ?? '';
    $logo = $brand['logo'] ?? null;
    $logoAlt = $brand['logo_alt'] ?? $brandName;
    $user = auth()->user();
@endphp
<aside
    class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full bg-portal-sidebar transition-[width,transform] duration-300 ease-in-out lg:translate-x-0 overflow-hidden flex flex-col"
    data-portal-sidebar
    aria-label="Navegação principal"
>
    <div
        class="flex items-center justify-between px-4 pt-4 pb-4 border-b border-white/10 dark:border-gray-700/50 transition-all duration-300"
        data-portal-sidebar-header
    >
        <a
            href="{{ Route::has(config('portal-ui.routes.home', 'home')) ? route(config('portal-ui.routes.home', 'home')) : url('/') }}"
            class="min-w-0 text-white dark:text-gray-100 no-underline flex flex-col items-start gap-2"
            data-portal-brand
        >
            <div
                class="flex items-center justify-center overflow-hidden transition-all duration-300 w-full max-w-[190px] min-h-11"
                data-portal-brand-mark
            >
                @if(! empty($logo))
                    <img
                        src="{{ asset($logo) }}"
                        alt="{{ $logoAlt }}"
                        class="object-contain transition-all duration-300 w-full max-h-12 object-left dark:brightness-90 dark:contrast-110"
                        data-portal-logo
                        loading="eager"
                    >
                @else
                    <div class="w-10 h-10 rounded-lg bg-white/15 dark:bg-white/10 flex items-center justify-center font-bold text-white dark:text-gray-100">
                        {{ strtoupper(substr($brandName, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div
                class="overflow-hidden leading-tight"
                data-portal-hide-collapsed
            >
                <h1 class="text-base font-bold text-white dark:text-gray-100 whitespace-nowrap">{{ $brandName }}</h1>
                @if($brandSubtitle !== '')
                    <p class="text-xs text-white/70 dark:text-gray-400 whitespace-nowrap">{{ $brandSubtitle }}</p>
                @endif
            </div>
        </a>

        @if(config('portal-ui.layout.sidebar_collapsible', true))
            <button
                type="button"
                class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-white/10 dark:bg-gray-700/50 hover:bg-white/20 dark:hover:bg-gray-700 transition-all text-white dark:text-gray-300"
                title="Recolher menu"
                aria-label="Alternar navegação lateral"
                aria-expanded="true"
                data-portal-sidebar-collapse
            >
                <i class="fa fa-chevron-left text-sm" data-portal-collapse-icon aria-hidden="true"></i>
            </button>
        @endif

        <button type="button" data-portal-sidebar-close class="lg:hidden text-white dark:text-gray-300 hover:bg-white/10 dark:hover:bg-gray-700 p-2 rounded-lg">
            <span class="sr-only">Fechar menu</span>
            <i class="fa fa-times"></i>
        </button>
    </div>

    <nav class="flex-1 min-h-0 overflow-y-auto py-4 px-3 dark:bg-gray-900/50 dark:border-gray-800" data-portal-sidebar-nav>
        @isset($sidebar)
            {{ $sidebar }}
        @else
            @yield('sidebar')

            @foreach($groups as $groupKey => $group)
                @php
                    $items = $group['items'] ?? [];
                    $groupLabel = $group['label'] ?? $groupKey;
                @endphp

                @if(count($items) > 0)
                    <div class="mb-6">
                        @if(! empty($groupLabel))
                            <h3 class="px-3 text-xs font-semibold text-white/60 dark:text-gray-400 uppercase tracking-wider mb-2" data-portal-hide-collapsed>
                                {{ $groupLabel }}
                            </h3>
                        @endif

                        <div class="space-y-1">
                            @foreach($items as $item)
                                <x-portal::sidebar-item
                                    :label="$item['label'] ?? 'Item'"
                                    :route="$item['route'] ?? null"
                                    :url="$item['url'] ?? null"
                                    :icon="$item['icon'] ?? 'fa-circle'"
                                    :active="$item['active'] ?? null"
                                    :can="$item['can'] ?? null"
                                    :guest="$item['guest'] ?? null"
                                    :external="$item['external'] ?? null"
                                    :children="$item['children'] ?? []"
                                    :href="$item['href'] ?? null"
                                    :is-active="$item['is_active'] ?? null"
                                    :is-external="$item['is_external'] ?? null"
                                    :target="$item['target'] ?? null"
                                    :rel="$item['rel'] ?? null"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endisset
    </nav>

    @if($user)
        <div class="border-t border-white/10 dark:border-gray-700/50 bg-black/10 dark:bg-gray-800/50 mt-auto">
            <div class="p-4">
                <div class="flex items-center gap-3" data-portal-profile>
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white/20 dark:bg-white/10 flex items-center justify-center text-white dark:text-gray-100 font-semibold">
                            {{ strtoupper(substr($user->name ?? $user->email ?? 'U', 0, 2)) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0" data-portal-hide-collapsed>
                        <p class="text-sm font-medium text-white dark:text-gray-100 truncate">{{ $user->name ?? 'Usuário' }}</p>
                        @if(! empty($user->email))
                            <p class="text-xs text-white/60 dark:text-gray-400 truncate">{{ $user->email }}</p>
                        @endif
                    </div>

                    @php($logoutRoute = config('portal-ui.routes.logout', 'logout'))
                    @if(Route::has($logoutRoute))
                        <form method="POST" action="{{ route($logoutRoute) }}">
                            @csrf
                            <button type="submit" class="text-white/60 dark:text-gray-400 hover:text-white dark:hover:text-gray-300 transition-colors" title="Sair">
                                <span class="sr-only">Sair</span>
                                <i class="fa fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</aside>
