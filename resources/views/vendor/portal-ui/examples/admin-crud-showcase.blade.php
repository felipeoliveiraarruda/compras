@extends('portal-ui::layouts.app')

@section('title', 'CRUD Administrativo')

@section('breadcrumbs')
    <a href="{{ Route::has('dashboard') ? route('dashboard') : (Route::has('home') ? route('home') : '#') }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Cadastros</span>
    <span>/</span>
    <span>CRUD Administrativo</span>
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
        title="CRUD Administrativo"
        subtitle="Exemplo de listagem, filtros, estados e ações para telas administrativas do portal."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => Route::has('dashboard') ? 'dashboard' : (Route::has('home') ? 'home' : '')],
            ['label' => 'Cadastros', 'route' => ''],
            ['label' => 'CRUD Administrativo', 'route' => ''],
        ]"
    >
        <x-slot name="actions">
            <x-portal::badge variant="warning">Admin</x-portal::badge>
            <x-portal::button variant="outline" icon="fa-download">Exportar</x-portal::button>
            <x-portal::button icon="fa-plus">Novo registro</x-portal::button>
        </x-slot>
    </x-portal::page-header>

    <div class="space-y-6">
        @if(collect($demoLinks)->contains(fn ($link) => \Illuminate\Support\Facades\Route::has($link['route'])))
            <x-portal::card>
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Navegação dos exemplos</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Compare os níveis minimal, simple, admin e guest no mesmo ambiente.</p>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        @foreach($demoLinks as $link)
                            @if(\Illuminate\Support\Facades\Route::has($link['route']))
                                <x-portal::button
                                    :href="route($link['route'])"
                                    :variant="$link['route'] === 'portal-ui.demo.crud' ? 'primary' : 'outline'"
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

        <x-portal::card padding="false">
            <x-portal::section-header
                title="Filtros"
                subtitle="Estrutura recomendada para busca, status e ações rápidas."
                icon="fa-filter"
            />

            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-4">
                <x-portal::input label="Buscar" name="busca" placeholder="Nome, código ou descrição" wrapperClass="mb-0" />
                <x-portal::select
                    label="Status"
                    name="status"
                    :options="['' => 'Todos', 'ativo' => 'Ativo', 'inativo' => 'Inativo']"
                    wrapperClass="mb-0"
                />
                <x-portal::select
                    label="Categoria"
                    name="categoria"
                    :options="['' => 'Todas', 'infra' => 'Infraestrutura', 'atendimento' => 'Atendimento', 'financeiro' => 'Financeiro']"
                    wrapperClass="mb-0"
                />
                <div class="flex flex-col gap-3 pt-0 md:justify-end md:pt-6">
                    <x-portal::button full="true" variant="secondary" icon="fa-eraser">Limpar filtros</x-portal::button>
                </div>
            </div>
        </x-portal::card>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <x-portal::card>
                <x-slot name="header">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Resumo rápido</span>
                </x-slot>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Registros ativos</span>
                        <x-portal::badge variant="success">184</x-portal::badge>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Pendentes de revisão</span>
                        <x-portal::badge variant="warning">12</x-portal::badge>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Arquivados</span>
                        <x-portal::badge variant="secondary">38</x-portal::badge>
                    </div>
                </div>
            </x-portal::card>

            <x-portal::card class="xl:col-span-2" padding="false">
                <x-portal::section-header
                    title="Ações em lote"
                    subtitle="Bloco útil para controles globais acima da tabela."
                    icon="fa-bolt"
                >
                    <x-slot name="actions">
                        <x-portal::button size="sm" variant="ghost" icon="fa-rotate-right">Sincronizar</x-portal::button>
                    </x-slot>
                </x-portal::section-header>

                <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <input type="checkbox" class="rounded border-gray-300 text-portal focus:ring-portal/30">
                            Selecionar todos
                        </label>
                        <x-portal::badge variant="info">24 itens selecionados</x-portal::badge>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <x-portal::button variant="outline" full="true" icon="fa-tag">Alterar categoria</x-portal::button>
                        <x-portal::button variant="danger" full="true" icon="fa-trash">Arquivar selecionados</x-portal::button>
                    </div>
                </div>
            </x-portal::card>
        </div>

        <x-portal::card padding="false">
            <x-portal::section-header
                title="Tabela de registros"
                subtitle="Exemplo de tabela com badges, switch e ações por linha."
                icon="fa-table"
            />

            <x-portal::table>
                <x-slot name="head">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Registro</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Categoria</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Publicado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ações</th>
                    </tr>
                </x-slot>

                <x-slot name="body">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" class="mt-1 rounded border-gray-300 text-portal focus:ring-portal/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Central de Serviços</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Código: CSV-001</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><x-portal::badge variant="info">Atendimento</x-portal::badge></td>
                            <td class="px-4 py-3"><x-portal::badge variant="success">Ativo</x-portal::badge></td>
                            <td class="px-4 py-3">
                                <x-portal::switch
                                    name="registro_1"
                                    checked="true"
                                    inlineLabel="Visivel"
                                    class="mb-0"
                                />
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-portal::resource-actions
                                    :view-href="'#'"
                                    :edit-href="'#'"
                                    :delete-onclick="'window.alert(&quot;Arquivar registro de demonstração&quot;)'"
                                />
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" class="mt-1 rounded border-gray-300 text-portal focus:ring-portal/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Painel de Contratos</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Código: FIN-014</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><x-portal::badge variant="secondary">Financeiro</x-portal::badge></td>
                            <td class="px-4 py-3"><x-portal::badge variant="warning">Revisao</x-portal::badge></td>
                            <td class="px-4 py-3">
                                <x-portal::switch
                                    name="registro_2"
                                    inlineLabel="Oculto"
                                    class="mb-0"
                                />
                            </td>
                            <td class="px-4 py-3 text-right">
                                <x-portal::resource-actions
                                    :view-href="'#'"
                                    :edit-href="'#'"
                                    :delete-onclick="'window.alert(&quot;Remover item do showcase&quot;)'"
                                    mode="label"
                                />
                            </td>
                        </tr>
                </x-slot>
            </x-portal::table>

            <x-portal::section-footer muted="true">
                <span>Mostrando 1-2 de 184 registros</span>
                <div class="flex items-center gap-2">
                    <x-portal::button size="sm" variant="outline">Anterior</x-portal::button>
                    <x-portal::button size="sm">1</x-portal::button>
                    <x-portal::button size="sm" variant="outline">2</x-portal::button>
                    <x-portal::button size="sm" variant="outline">Próxima</x-portal::button>
                </div>
            </x-portal::section-footer>
        </x-portal::card>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <x-portal::card>
                <x-slot name="header">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Estado vazio</span>
                </x-slot>

                <x-portal::empty-state
                    title="Nenhum item encontrado"
                    message="Use este padrão para buscas sem resultado ou módulos ainda vazios."
                    icon="fa-inbox"
                />
            </x-portal::card>

            <x-portal::card>
                <x-slot name="header">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Alertas de contexto</span>
                </x-slot>

                <div class="space-y-3">
                    <x-portal::alert variant="warning" title="Sincronização pendente">
                        Existem registros externos aguardando reconciliação antes da próxima publicação.
                    </x-portal::alert>
                    <x-portal::alert variant="danger" title="Ação bloqueada">
                        Alguns itens não podem ser excluídos porque ainda possuem vínculos ativos.
                    </x-portal::alert>
                </div>
            </x-portal::card>
        </div>

        <x-portal::card padding="false">
            <x-portal::section-header
                title="Confirmação e ações"
                subtitle="Demonstra o uso combinado de resource-actions, table-actions e confirm-modal."
                icon="fa-bolt"
            >
                <x-slot name="actions">
                    <x-portal::button
                        type="button"
                        size="sm"
                        variant="outline"
                        icon="fa-box-archive"
                        data-portal-modal-open="crud-confirm-demo"
                    >
                        Abrir confirmação
                    </x-portal::button>
                </x-slot>
            </x-portal::section-header>

            <div class="grid grid-cols-1 gap-4 p-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-dashed border-gray-300 p-4 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Ações compactas</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ideal para colunas finais em tabelas densas.</p>
                    <div class="mt-4 flex justify-end">
                        <x-portal::resource-actions
                            :view-href="'#'"
                            :edit-href="'#'"
                            :delete-onclick="'window.alert(&quot;Excluir registro de exemplo&quot;)'"
                        />
                    </div>
                </div>

                <div class="rounded-2xl border border-dashed border-gray-300 p-4 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Ações com rótulo</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Melhor quando a ação precisa ficar mais explícita fora da tabela.</p>
                    <div class="mt-4 flex flex-wrap justify-end gap-2">
                        <x-portal::resource-actions
                            :view-href="'#'"
                            :edit-href="'#'"
                            :delete-onclick="'window.alert(&quot;Arquivar item do showcase&quot;)'"
                            mode="label"
                        />
                    </div>
                </div>
            </div>
        </x-portal::card>
    </div>

    <x-portal::confirm-modal
        id="crud-confirm-demo"
        class="is-hidden"
        title="Confirmar arquivamento"
        message="Este é um exemplo visual do confirm-modal para fluxos críticos do sistema."
        confirm-label="Arquivar item"
        confirm-variant="danger"
        confirm-icon="fa-box-archive"
    />
@endsection
