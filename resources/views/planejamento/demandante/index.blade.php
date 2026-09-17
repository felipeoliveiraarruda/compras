<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Pca;
use App\Models\Demandante;
use Uspdev\Replicado\Pessoa;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public bool $showDeleteModal = false;
    public ?int $pcaIdToDelete = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function cleanFilters(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function confirmDelete(int $codigoPca): void
    {
        $this->pcaIdToDelete = $codigoPca;
        $this->showDeleteModal = true;
    }

    public function deleteAllDemandantesFromPca(): void
    {
        if ($this->pcaIdToDelete) {
            Demandante::where('codigoPca', $this->pcaIdToDelete)->delete();
            session()->flash('success', 'Demandantes da contratação removidos com sucesso!');
        }

        $this->showDeleteModal = false;
        $this->pcaIdToDelete = null;
    }

    public function render()
    {
        // Consulta agrupando por PCA e trazendo todas as áreas requisitantes agregadas por |||
        $pcas = Pca::query()
            ->has('demandantes')
            ->with('demandantes')
            ->select('pca.*')
            ->selectRaw("GROUP_CONCAT(DISTINCT pca.areaRequisitante SEPARATOR '|||') as areas_requisitantes")
            ->when($this->search, function ($query) {
                $term = '%' . trim($this->search) . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('pca.numeroArtefato', 'like', $term)
                      ->orWhere('pca.anoArtefato', 'like', $term)
                      ->orWhere('pca.numeroContratacao', 'like', $term)
                      ->orWhere('pca.tituloContratacao', 'like', $term)
                      ->orWhere('pca.areaRequisitante', 'like', $term)
                      ->orWhereHas('demandantes', function ($dq) use ($term) {
                          $dq->where('codigoPessoa', 'like', $term);
                      });
                });
            })
            ->groupBy('pca.codigoUASG', 'pca.numeroContratacao')
            ->paginate(15);

        // Busca o nome dos demandantes no Replicado
        $pcas->getCollection()->transform(function ($pca) {
            $pca->demandantes->transform(function ($demandante) {
                try {
                    $pessoa = Pessoa::dump($demandante->codigoPessoa);
                    $demandante->nomePessoa = $pessoa['nompes'] ?? 'Nome não encontrado';
                } catch (\Throwable $e) {
                    $demandante->nomePessoa = 'Não encontrado no Replicado';
                }
                return $demandante;
            });
            return $pca;
        });

        return view('planejamento.demandante.index', [
            'pcas' => $pcas,
        ]);
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('admin') ? route('admin') : '#' }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Demandantes por Contratação</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header title="Demandantes por Contratação" subtitle="Visualização das contratações e seus respectivos demandantes.">
        <x-slot:actions>
            <x-portal::button :href="route('demandantes.create')" icon="fa-plus">Novo</x-portal::button>
        </x-slot:actions>
    </x-portal::page-header>

    <x-portal::flash-messages />

    {{-- Tabela Agrupada por Contratação --}}
    @if($pcas->isEmpty())
        <x-portal::alert variant="warning" title=" ">
            @if($search)
                Nenhuma contratação com demandantes encontrada para "{{ $search }}".
            @else
                Nenhum demandante cadastrado no momento.
            @endif
        </x-portal::alert>    
    @else
        {{-- Filtros de Pesquisa --}}
        <x-portal::card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-3">
                    <x-portal::input
                        label="Buscar"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Pesquise por contratação, artefato, título, área ou Nº USP..."
                        wrapperClass="mb-0"
                    />
                </div>

                <div class="flex flex-col justify-end">
                    <x-portal::button full="true" variant="secondary" icon="fa-eraser" wire:click="cleanFilters">
                        Limpar filtros
                    </x-portal::button>
                </div>
            </div>
        </x-portal::card>

        <x-portal::card padding="false">
            {{-- Barra de Paginação Customizada no Topo --}}
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Exibindo 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->firstItem() ?? 0 }}</span> 
                    a 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->lastItem() ?? 0 }}</span> 
                    de 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->total() }}</span> 
                    contratações
                </div>

                <div class="flex items-center space-x-2">
                    @if ($pcas->onFirstPage())
                        <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded cursor-not-allowed">Anterior</span>
                    @else
                        <button type="button" wire:click="previousPage" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 transition">Anterior</button>
                    @endif

                    @if ($pcas->hasMorePages())
                        <button type="button" wire:click="nextPage" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 transition">Próximo</button>
                    @else
                        <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded cursor-not-allowed">Próximo</span>
                    @endif
                </div>
            </div>

            {{-- TABELA --}}
            <x-portal::table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Contratação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Título / Área(s)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Demandantes Vinculados</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Ações</th>
                    </tr>
                </x-slot:head>

                <x-slot:body>
                    @foreach($pcas as $pca)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                            {{-- Código da Contratação --}}
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-bold whitespace-nowrap">
                                {{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }}
                            </td>

                            {{-- Título e Múltiplas Áreas Requisitantes --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $pca->tituloContratacao }}</div>
                                
                                @php
                                    $areas = array_filter(array_map('trim', explode('|||', $pca->areas_requisitantes ?? $pca->areaRequisitante ?? '')));
                                @endphp

                                <div class="mt-1 flex flex-wrap items-center gap-1">
                                    <span class="text-xs text-gray-500 font-medium mr-1">Área(s):</span>
                                    @forelse($areas as $area)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            {{ $area }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic">Não informada</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Lista de Demandantes --}}
                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap gap-1.5 max-w-md">
                                    @foreach($pca->demandantes as $dem)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            {{ $dem->nomePessoa }} <span class="ml-1 text-blue-500 text-[10px]">({{ $dem->codigoPessoa }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Ações --}}
                            <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                <x-portal::button
                                    :href="route('demandantes.edit', $pca->codigoPca)"
                                    variant="secondary"
                                    size="xs"
                                    icon="fa-pen-to-square"
                                >
                                    Editar
                                </x-portal::button>

                                <x-portal::button
                                    type="button"
                                    variant="danger"
                                    size="xs"
                                    icon="fa-trash"
                                    wire:click="confirmDelete({{ $pca->codigoPca }})"
                                >
                                    Excluir
                                </x-portal::button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-portal::table>
        </x-portal::card>    
    @endif

    {{-- Modal Confirmação Exclusão --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Confirmar Exclusão</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Deseja remover <strong>todos os demandantes</strong> vinculados a esta contratação?
                </p>
                <div class="flex justify-end space-x-3 pt-2">
                    <x-portal::button type="button" variant="secondary" wire:click="$set('showDeleteModal', false)">
                        Cancelar
                    </x-portal::button>
                    <x-portal::button type="button" variant="danger" icon="fa-trash" wire:click="deleteAllDemandantesFromPca">
                        Excluir Todos
                    </x-portal::button>
                </div>
            </div>
        </div>
    @endif
</div>