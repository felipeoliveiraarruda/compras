@extends('portal-ui::layouts.guest')

@section('title', 'Exemplo Visitante')

@section('content')
    @php
        $demoLinks = [
            ['label' => 'Minimal', 'route' => 'portal-ui.demo.minimal'],
            ['label' => 'Simple', 'route' => 'portal-ui.demo'],
            ['label' => 'Admin', 'route' => 'portal-ui.demo.crud'],
            ['label' => 'Guest', 'route' => 'portal-ui.demo.guest'],
        ];
    @endphp

    <div class="space-y-6">
        @if(collect($demoLinks)->contains(fn ($link) => \Illuminate\Support\Facades\Route::has($link['route'])))
            <x-portal::card>
                <div class="flex flex-col gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Navegação dos exemplos</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Atalhos úteis para comparar os níveis minimal, simple, admin e guest.</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        @foreach($demoLinks as $link)
                            @if(\Illuminate\Support\Facades\Route::has($link['route']))
                                <x-portal::button
                                    :href="route($link['route'])"
                                    :variant="$link['route'] === 'portal-ui.demo.guest' ? 'primary' : 'outline'"
                                    size="sm"
                                    full="true"
                                >
                                    {{ $link['label'] }}
                                </x-portal::button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-portal::card>
        @endif

        <div class="text-center">
            <div class="mb-3 flex justify-center">
                <x-portal::badge variant="info">Guest</x-portal::badge>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Exemplo de Tela Visitante</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Uso sugerido para login, recuperação de senha e acessos públicos.</p>
        </div>

        <x-portal::alert variant="info" title="Acesso público">
            Este exemplo usa o layout `portal-ui::layouts.guest`.
        </x-portal::alert>

        <form class="space-y-4">
            <x-portal::input label="E-mail" name="email" type="email" placeholder="voce@exemplo.org" />
            <x-portal::input label="Senha" name="password" type="password" placeholder="••••••••" />
            <x-portal::button type="submit" full="true" icon="fa-sign-in-alt">Entrar</x-portal::button>
        </form>
    </div>
@endsection
