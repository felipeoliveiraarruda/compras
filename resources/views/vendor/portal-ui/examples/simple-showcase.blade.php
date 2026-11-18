@extends('portal-ui::layouts.app')

@section('title', 'Showcase Simple')

@section('breadcrumbs')
    <a href="{{ Route::has('dashboard') ? route('dashboard') : (Route::has('home') ? route('home') : '#') }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Showcase Simple</span>
@endsection

@section('content')
    @php
        $demoLinks = [
            ['label' => 'Minimal', 'route' => 'portal-ui.demo.minimal'],
            ['label' => 'Simple', 'route' => 'portal-ui.demo'],
            ['label' => 'Admin', 'route' => 'portal-ui.demo.crud'],
            ['label' => 'Guest', 'route' => 'portal-ui.demo.guest'],
        ];
    @endphp

    <x-portal::page-header
        title="Showcase Simple"
        subtitle="Exemplo interno intermediário com cabeçalhos, cards, tabela e formulário."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => Route::has('dashboard') ? 'dashboard' : (Route::has('home') ? 'home' : '')],
            ['label' => 'Showcase Simple', 'route' => ''],
        ]"
    >
        <x-slot name="actions">
            <x-portal::badge variant="primary">Simple</x-portal::badge>
            <x-portal::button variant="outline" icon="fa-rotate-right">Atualizar</x-portal::button>
            <x-portal::button icon="fa-plus">Novo registro</x-portal::button>
        </x-slot>
    </x-portal::page-header>

    <div class="space-y-6">
        @if(collect($demoLinks)->contains(fn ($link) => \Illuminate\Support\Facades\Route::has($link['route'])))
            <x-portal::card>
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Navegação dos exemplos</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Use estes atalhos para comparar os níveis minimal, simple, admin e guest.</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        @foreach($demoLinks as $link)
                            @if(\Illuminate\Support\Facades\Route::has($link['route']))
                                <x-portal::button
                                    :href="route($link['route'])"
                                    :variant="$link['route'] === 'portal-ui.demo' ? 'primary' : 'outline'"
                                    size="sm"
                                >
                                    {{ $link['label'] }}
                                </x-portal::button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-portal::card>
        @endif

        <x-portal::alert variant="success" title="Pacote carregado">
            Os componentes do tema estão sendo renderizados a partir do namespace `portal-ui::`.
        </x-portal::alert>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <x-portal::card>
                <x-slot name="header">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold text-gray-900 dark:text-gray-100">Solicitações abertas</span>
                        <x-portal::badge variant="warning">24</x-portal::badge>
                    </div>
                </x-slot>

                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">128</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Chamados aguardando tratamento.</p>
            </x-portal::card>

            <x-portal::card>
                <x-slot name="header">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold text-gray-900 dark:text-gray-100">Tempo médio</span>
                        <x-portal::badge variant="success">Dentro do SLA</x-portal::badge>
                    </div>
                </x-slot>

                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">2h 14m</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Média das últimas 48 horas.</p>
            </x-portal::card>

            <x-portal::card>
                <x-slot name="header">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold text-gray-900 dark:text-gray-100">Satisfação</span>
                        <x-portal::badge variant="primary">NPS</x-portal::badge>
                    </div>
                </x-slot>

                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">87%</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Avaliação positiva dos usuários.</p>
            </x-portal::card>
        </div>

        <x-portal::card padding="false">
            <x-portal::section-header
                title="Tabela de Exemplo"
                subtitle="Estrutura sugerida para listagens administrativas."
                icon="fa-table"
            >
                <x-slot name="actions">
                    <x-portal::button size="sm" variant="ghost" icon="fa-filter">Filtros</x-portal::button>
                </x-slot>
            </x-portal::section-header>

            <x-portal::table>
                <x-slot name="head">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nome</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Área</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ações</th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">Portal de Atendimento</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">Suporte</td>
                            <td class="px-4 py-3"><x-portal::badge variant="success">Ativo</x-portal::badge></td>
                            <td class="px-4 py-3 text-right">
                                <x-portal::resource-actions
                                    :view-href="'#'"
                                    :edit-href="'#'"
                                    only="view,edit"
                                />
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">Painel Financeiro</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">Financeiro</td>
                            <td class="px-4 py-3"><x-portal::badge variant="warning">Manutenção</x-portal::badge></td>
                            <td class="px-4 py-3 text-right">
                                <x-portal::resource-actions
                                    :view-href="'#'"
                                    :edit-href="'#'"
                                    :delete-onclick="'window.alert(&quot;Excluir item de demonstração&quot;)'"
                                    mode="label"
                                />
                            </td>
                        </tr>
                </x-slot>
            </x-portal::table>

            <x-portal::section-footer muted="true">
                <span>Resumo de ações reaproveitáveis para listagens simples.</span>
                <x-portal::resource-actions
                    :view-href="'#'"
                    :edit-href="'#'"
                    only="view,edit"
                />
            </x-portal::section-footer>
        </x-portal::card>

        <x-portal::card padding="false">
            <x-portal::section-header
                title="Formulário de Exemplo"
                subtitle="Uso combinado dos componentes de entrada do pacote."
                icon="fa-file-lines"
            />

            <form class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                <x-portal::input label="Nome do sistema" name="nome" placeholder="Portal de Atendimento" />
                <x-portal::select
                    label="Status"
                    name="status"
                    :options="['ativo' => 'Ativo', 'manutencao' => 'Manutenção', 'inativo' => 'Inativo']"
                    selected="ativo"
                />
                <div class="md:col-span-2">
                    <x-portal::textarea label="Descrição" name="descricao">Sistema usado como demonstração visual do tema.</x-portal::textarea>
                </div>
                <div class="md:col-span-2 flex flex-col gap-3 border-t border-gray-200 pt-4 sm:flex-row sm:justify-end dark:border-gray-700">
                    <x-portal::button variant="secondary" full="true">Cancelar</x-portal::button>
                    <x-portal::button full="true" icon="fa-save">Salvar alterações</x-portal::button>
                </div>
            </form>
        </x-portal::card>
    </div>
@endsection
