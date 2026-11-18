@php
    $user = auth()->user();
    $loginRoute = config('portal-ui.routes.login', 'login');
    $logoutRoute = config('portal-ui.routes.logout', 'logout');
    $hasBreadcrumbs = isset($breadcrumbs) || $__env->hasSection('breadcrumbs');
    $hasSidebar = $hasSidebar ?? true;
@endphp
<header class="sticky top-0 z-40 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-4 min-w-0">
                @if($hasSidebar)
                    <button
                        type="button"
                        data-portal-sidebar-open
                        class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-all"
                    >
                        <span class="sr-only">Abrir menu</span>
                        <i class="fa fa-bars text-xl"></i>
                    </button>
                @endif

                @if($hasBreadcrumbs)
                    <nav class="hidden sm:flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 min-w-0" aria-label="Caminho de navegação">
                        @isset($breadcrumbs)
                            {{ $breadcrumbs }}
                        @else
                            @yield('breadcrumbs')
                        @endisset
                    </nav>
                @endif
            </div>

            <div class="flex items-center gap-3">
                @isset($actions)
                    <div class="hidden md:flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @else
                    @hasSection('topbar-actions')
                        <div class="hidden md:flex items-center gap-2">
                            @yield('topbar-actions')
                        </div>
                    @endif
                @endisset

                <button
                    type="button"
                    data-portal-dark-mode-toggle
                    class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg transition-all flex items-center justify-center w-10 h-10"
                    aria-label="{{ $darkModeLabel ?? 'Alternar tema escuro' }}"
                >
                    <i class="fa fa-moon text-lg dark:hidden"></i>
                    <i class="fa fa-sun text-lg hidden dark:block text-yellow-500"></i>
                </button>

                @if($user)
                    <div class="relative" data-portal-dropdown>
                        <button
                            type="button"
                            data-portal-dropdown-toggle
                            aria-expanded="false"
                            class="flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg p-2 transition-all"
                        >
                            <div class="w-8 h-8 rounded-full bg-portal-gradient flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($user->name ?? $user->email ?? 'U', 0, 2)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ explode(' ', $user->name ?? 'Usuário')[0] }}
                            </span>
                            <i class="fa fa-chevron-down text-xs text-gray-400 dark:text-gray-500 hidden md:block"></i>
                        </button>

                        <div
                            class="hidden absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50"
                            data-portal-dropdown-menu
                        >
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $user->name ?? 'Usuário' }}</p>
                                @if(! empty($user->email))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                @endif
                            </div>

                            @isset($userMenu)
                                <div class="py-1">
                                    {{ $userMenu }}
                                </div>
                            @else
                                @hasSection('user-menu')
                                    <div class="py-1">
                                        @yield('user-menu')
                                    </div>
                                @endif
                            @endisset

                            @if(Route::has($logoutRoute))
                                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route($logoutRoute) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition-all"
                                    >
                                        <i class="fa fa-sign-out-alt w-4"></i>
                                        <span>Sair</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @elseif(Route::has($loginRoute))
                    <a
                        href="{{ route($loginRoute) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-portal-gradient text-white text-sm font-medium rounded-lg hover:brightness-110 transition-all shadow-sm"
                    >
                        <i class="fa fa-sign-in-alt"></i>
                        <span>Entrar</span>
                    </a>
                @endif
            </div>
        </div>

        @if($hasBreadcrumbs)
            <div class="sm:hidden pb-3">
                <nav class="flex items-center gap-2 overflow-x-auto whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" aria-label="Caminho de navegação">
                    @isset($breadcrumbs)
                        {{ $breadcrumbs }}
                    @else
                        @yield('breadcrumbs')
                    @endisset
                </nav>
            </div>
        @endif
    </div>
</header>
