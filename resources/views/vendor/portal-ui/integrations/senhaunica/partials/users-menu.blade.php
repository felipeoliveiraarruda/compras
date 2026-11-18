@push('portal-ui-head')
    <style>
        .senhaunica-badge-destroy {
            color: #fef3c7 !important;
        }

        .senhaunica-badge-prod {
            color: #cffafe !important;
        }

        .dark .senhaunica-badge-destroy {
            color: #111827 !important;
        }

        .dark .senhaunica-badge-prod {
            color: #111827 !important;
        }
    </style>
@endpush

<div class="flex flex-wrap items-center gap-3 mb-2">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
        <span class="hidden sm:inline">Senhaunica-socialite</span>
        <span class="sm:hidden">SS</span>
        <i class="fas fa-angle-right text-sm ml-1"></i> Usuários
    </h1>
    <div class="flex flex-wrap gap-1">
        @if (hasReplicado())
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300" title="Usando replicado">replicado</span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Replicado indisponível"><s>replicado</s></span>
        @endif

        @if (config('senhaunica.permission'))
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300" title="Usando permissões internas">permission</span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Permissões internas desativadas"><s>permission</s></span>
        @endif

        @if (config('senhaunica.dropPermissions'))
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300" title="Permissões gerenciadas pelo .env">drop <span class="hidden sm:inline">permissions</span></span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Permissões gerenciadas pela aplicação"><s>drop <span class="hidden sm:inline">permissions</span></s></span>
        @endif

        @if (config('senhaunica.onlyLocalUsers'))
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300" title="Somente usuários locais">Local User</span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Qualquer usuário pode logar"><s>Local User</s></span>
        @endif

        @if (config('senhaunica.destroyUser'))
            <span class="senhaunica-badge-destroy inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900/60" title="Remover usuário habilitado">destroy</span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Remover usuário indisponível"><s>destroy</s></span>
        @endif

        @if (config('senhaunica.debug'))
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300" title="Modo debug habilitado">debug</span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400" title="Modo debug desativado"><s>debug</s></span>
        @endif

        @if (config('senhaunica.dev') != 'no')
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300" title="{{ config('senhaunica.dev') }}">dev</span>
        @else
            <span class="senhaunica-badge-prod inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 dark:bg-cyan-900/60" title="Oauth em ambiente de produção">prod</span>
        @endif
    </div>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300 rounded-lg p-4 mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex flex-wrap items-center gap-2 mt-2">
    @include('senhaunica::partials.filterbox')
    @include('senhaunica::partials.users-add')
    @include('senhaunica::partials.local-users-add')
</div>
