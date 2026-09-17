<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marca
    |--------------------------------------------------------------------------
    |
    | Valores genéricos usados pelos layouts do pacote. Cada sistema consumidor
    | pode publicar este arquivo e sobrescrever nome, subtítulo, logo e favicon.
    |
    */

    'brand' => [
        'name' => env('PORTAL_UI_BRAND_NAME', config('app.name', 'Sistema')),
        'subtitle' => env('PORTAL_UI_BRAND_SUBTITLE', ''),
        'logo' => env('PORTAL_UI_LOGO'),
        'logo_alt' => env('PORTAL_UI_LOGO_ALT', env('PORTAL_UI_BRAND_NAME', config('app.name', 'Sistema'))),
        'favicon' => env('PORTAL_UI_FAVICON', 'favicon.ico'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tokens Visuais
    |--------------------------------------------------------------------------
    |
    | O CSS compilado do pacote deve consumir estes valores como referência de
    | customização. Tailwind pode ser usado no build do pacote, mas não deve ser
    | obrigatório para o app consumidor.
    |
    */

    'colors' => [
        'primary' => env('PORTAL_UI_PRIMARY', '#0b7c93'),
        'primary_dark' => env('PORTAL_UI_PRIMARY_DARK', '#095f71'),
        'primary_soft' => env('PORTAL_UI_PRIMARY_SOFT', '#0d94a8'),
    ],

    'layout' => [
        'sidebar_collapsible' => true,
        'body_class' => 'bg-gray-50',
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | O modo padrão é carregar assets publicados em public/vendor/portal-ui.
    | Apps com Vite/Tailwind podem optar por integrar os fontes do pacote.
    |
    */

    'assets' => [
        'mode' => env('PORTAL_UI_ASSET_MODE', 'published'),
        'load_css' => true,
        'load_js' => true,
        'css_path' => 'vendor/portal-ui/portal-ui.css',
        'js_path' => 'vendor/portal-ui/portal-ui.js',
        'fontawesome_cdn' => env('PORTAL_UI_FONTAWESOME_CDN', false),
        'fontawesome_cdn_url' => 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css',
    ],

    /*
    |--------------------------------------------------------------------------
    | Navegação
    |--------------------------------------------------------------------------
    |
    | O pacote não fornece menus de domínio. O consumidor pode configurar itens
    | com route, url, icon, active e can, ou substituir a navegação por slots.
    |
    */

    'navigation' => [
        'groups' => 
        [
            'publico' => 
            [
                'label' => 'Menu Principal',
                'items' => 
                [
                    [
                        'label' => 'Início',
                        'route' => 'home',
                        'icon' => 'fa-home',
                        'active' => 'home',
                        'guest' => true,
                    ],
                ],                
            ],

            'admin' => 
            [
                'label' => 'Administração',
                'items' => 
                [
                    [
                        'label' => 'Dashboard',
                        'route' => 'admin',
                        'icon' => 'fa-tachometer-alt',
                        'active' => 'admin',
                        'can'   => 'user',
                    ],                
                ],
            ],               

            'planejamento' => 
            [
                'label' => 'Planejamento',
                'items' => 
                [
                    [
                        'label' => 'Demandantes',
                        'route' => 'demandantes',
                        'icon' => 'fa-solid fa-users-gear',
                        'active' => 'demandantes',
                        'can'   => 'user',
                    ],                    
                ],
            ],            

        ],
        'hide_missing_routes' => true,
    ],

    'routes' => [
        'login' => 'login',
        'logout' => 'logout',
        'home' => 'home',
    ],

    /*
    |--------------------------------------------------------------------------
    | Integrações
    |--------------------------------------------------------------------------
    |
    | Integrações opcionais com pacotes comuns nos sistemas consumidores. Quando
    | A integração SenhaUnica é habilitada automaticamente quando o service
    | provider da dependência está disponível. Overrides locais mantêm prioridade.
    |
    */

    'integrations' => [
        'senhaunica' => [
            'provider' => \Uspdev\SenhaunicaSocialite\SenhaunicaServiceProvider::class,
            'enabled' => env(
                'PORTAL_UI_SENHAUNICA_VIEWS',
                class_exists(\Uspdev\SenhaunicaSocialite\SenhaunicaServiceProvider::class)
            ),
            'layout' => env('PORTAL_UI_SENHAUNICA_LAYOUT', 'portal-ui::layouts.app'),
        ],
    ],

    'flash' => [
        'success_keys' => ['success', 'message'],
        'error_keys' => ['error', 'danger'],
        'warning_keys' => ['warning'],
        'info_keys' => ['info', 'status'],
        'persistent_keys' => [
            'success' => ['message_persistent'],
            'error' => ['error_persistent'],
            'warning' => ['warning_persistent'],
        ],
        'title_keys' => [
            'success' => ['success_title', 'message_title'],
            'error' => ['error_title'],
            'warning' => ['warning_title'],
            'info' => ['info_title'],
        ],
        'id_keys' => [
            'success' => [
                'success' => 'success_id',
                'message' => 'message_id',
            ],
            'error' => [
                'error' => 'error_id',
                'danger' => 'danger_id',
            ],
            'warning' => [
                'warning' => 'warning_id',
            ],
            'info' => [
                'info' => 'info_id',
                'status' => 'status_id',
            ],
        ],
    ],
];
