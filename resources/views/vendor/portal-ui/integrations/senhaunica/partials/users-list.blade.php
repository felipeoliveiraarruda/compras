@push('portal-ui-head')
    <style>
        .col-permission { text-align: right; width: 130px; }
        .col-button { width: 30px; text-align: center; }
    </style>
@endpush

<div class="mt-3 w-full overflow-x-auto rounded-xl border border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
    <table class="w-full min-w-full text-sm">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-700/50">
                @foreach ($columns as $column)
                    <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">@sortablelink($column['key'], $column['text'])</th>
                @endforeach

                @if (!empty(config('senhaunica.customUserField')))
                    @foreach (config('senhaunica.customUserField') as $cuf)
                        <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200" style="width: {{ $cuf['width'] }}">
                            @if (!empty($cuf['key']))
                                @sortablelink($cuf['key'], $cuf['label'])
                            @else
                                {{ $cuf['label'] }}
                            @endif
                        </th>
                    @endforeach
                @endif

                @if (config('senhaunica.permission'))
                    <th colspan="4" class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Permissões (Hierárquico | Vínculo | Função | Aplicação)</th>
                @endif

                @if (config('senhaunica.destroyUser'))
                    <th class="px-1 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Remover</th>
                @endif

                <th class="px-1 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Json</th>

                @if (!config('senhaunica.disableLoginas'))
                    <th class="px-1 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">
                        <span class="sm:hidden">Assumir identidade</span>
                        <span class="hidden sm:inline">Ident.</span>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800">
            @foreach ($users as $user)
                <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    @foreach ($columns as $column)
                        <td class="px-3 py-2 text-gray-800 dark:text-gray-200">
                            @if ($column['key'] === 'name' && $user->local === 1)
                                {{ $user->{$column['key']} }}
                                <button type="button"
                                    title="Alteração - Usuário Local"
                                    class="text-portal hover:text-portal-dark p-0"
                                    data-portal-local-user-edit
                                    data-url="{{ route(config('senhaunica.localUserRoutes') . '.edit', $user->id) }}"
                                    data-action="{{ route(config('senhaunica.localUserRoutes') . '.update', $user->id) }}">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                </button>
                            @else
                                {{ $user->{$column['key']} }}
                            @endif
                        </td>
                    @endforeach

                    @if (!empty(config('senhaunica.customUserField')))
                        @foreach (config('senhaunica.customUserField') as $cuf)
                            <td class="px-3 py-2">@includeIf($cuf['view'])</td>
                        @endforeach
                    @endif

                    @if (config('senhaunica.permission'))
                        <td class="px-3 py-2">@include('senhaunica::partials.permissoes-badge')</td>
                        <td class="px-3 py-2">@include('senhaunica::users.partials.permissoes-vinculo-btn')</td>
                        <td class="px-3 py-2">@include('senhaunica::users.partials.permissoes-funcao-btn')</td>
                        <td class="px-3 py-2">@include('senhaunica::users.partials.permissoes-aplicacao-btn')</td>
                    @endif

                    @if (config('senhaunica.destroyUser'))
                        <td class="col-button px-1 py-2">@include('senhaunica::partials.destroy-user-btn')</td>
                    @endif

                    <td class="col-button px-1 py-2">@include('senhaunica::partials.show-json-btn')</td>

                    @if (!config('senhaunica.disableLoginas'))
                        <td class="col-button px-1 py-2">@include('senhaunica::partials.assumir-identidade-btn')</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $users->appends($params)->links() }}
</div>

@yield('bottom_senhaunica_users')
