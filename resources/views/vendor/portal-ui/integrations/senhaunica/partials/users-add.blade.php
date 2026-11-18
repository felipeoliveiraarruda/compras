@push('portal-ui-head')
    <style>
        .senhaunica-loginas-btn {
            background: linear-gradient(135deg, #eab308 0%, #d97706 100%);
            color: #fff;
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.22);
        }

        .senhaunica-loginas-btn:hover {
            filter: brightness(1.05);
        }
    </style>
@endpush

<button
    type="button"
    data-portal-modal-open="senhaunica-user-create"
    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium border border-portal text-portal dark:text-cyan-300 rounded-lg hover:bg-portal hover:text-white dark:hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed senhaunicaUseraddBtn"
    @if(! hasReplicado()) disabled title="Necessário Replicado para adicionar usuários" @endif
>
    <x-portal::icon name="fa-plus" class="text-xs" />
    Adicionar
    @if(! config('senhaunica.disableLoginas'))
        / Assumir
    @endif
</button>

<x-portal::modal
    id="senhaunica-user-create"
    title="Adicionar Pessoas"
    icon="fa-user-plus"
    max-width="2xl"
    class="is-hidden"
>
    <form method="POST" action="{{ route(config('senhaunica.userRoutes').'.store') }}" class="space-y-4">
        @csrf
        @include('senhaunica::components.select-pessoa', [
            'label' => 'Pessoa',
            'groupClass' => 'mb-0',
        ])

        <div class="flex flex-wrap items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
            @if(! config('senhaunica.disableLoginas'))
                <button type="submit" name="loginas" value="1" class="senhaunica-loginas-btn inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold rounded-xl transition-all">
                    Assumir identidade
                </button>
            @endif

            <x-portal::button type="button" variant="secondary" data-portal-modal-close>
                Cancelar
            </x-portal::button>
            <x-portal::button type="submit" icon="fa-save">
                Salvar
            </x-portal::button>
        </div>
    </form>
</x-portal::modal>
