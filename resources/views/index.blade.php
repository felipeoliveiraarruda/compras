<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Pca;
use Uspdev\Replicado\Pessoa;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function cleanFilters(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function render()
    {
        // Consulta unificada compatível com SQL estrito e agregação de dados
        $pcas = Pca::query()
            ->select(
                'codigoUASG',
                'numeroContratacao',
                DB::raw('MIN(codigoPca) as codigoPca'),
                DB::raw('MAX(tituloContratacao) as tituloContratacao'),
                DB::raw('MAX(situacaoContratacao) as situacaoContratacao'),
                DB::raw('MAX(processoSei) as processoSei'),
                DB::raw('COUNT(DISTINCT numeroDfd) as total_itens'),
                DB::raw("GROUP_CONCAT(DISTINCT areaRequisitante SEPARATOR '|||') as areas_requisitantes")
            )
            ->with(['demandantes'])
            ->where('statusContratacao', 'Aprovada')
            ->when($this->search, function ($query) {
                $term = '%' . trim($this->search) . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('numeroArtefato', 'like', $term)
                      ->orWhere('anoArtefato', 'like', $term)
                      ->orWhere('numeroContratacao', 'like', $term)
                      ->orWhere('tituloContratacao', 'like', $term)
                      ->orWhere('areaRequisitante', 'like', $term)
                      ->orWhere('situacaoContratacao', 'like', $term)
                      ->orWhere('codigoUASG', 'like', $term)
                      ->orWhereHas('demandantes', function ($dq) use ($term) {
                          $dq->where('codigoPessoa', 'like', $term);
                      });
                });
            })
            ->groupBy('codigoUASG', 'numeroContratacao')
            ->paginate(15);

        return view('index', [
            'pcas' => $pcas,
        ]);
    }
};
?>

<div class="space-y-6">
    {{-- Filtros de Pesquisa --}}
    <x-portal::card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-3">
                <x-portal::input
                    label="Buscar"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Pesquise por UASG, nº da contratação, artefato, título, área ou demandante..."
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

    {{-- Lista de Resultados --}}
    @if($pcas->isEmpty())
        <x-portal::alert variant="warning" title=" ">
            @if($search)
                Nenhum projeto encontrado para a busca "{{ $search }}".
            @else
                Nenhum projeto disponível no momento.
            @endif
        </x-portal::alert>    
    @else
        <x-portal::card padding="false">
            {{-- BARRA SUPERIOR: Paginação customizada exibida sempre --}}
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Exibindo 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->firstItem() ?? 0 }}</span> 
                    a 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->lastItem() ?? 0 }}</span> 
                    de 
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->total() }}</span> 
                    resultados
                </div>

                <div class="flex items-center space-x-2">
                    @if ($pcas->onFirstPage())
                        <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded cursor-not-allowed">
                            Anterior
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Anterior
                        </button>
                    @endif

                    @if ($pcas->hasMorePages())
                        <button type="button" wire:click="nextPage" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Próximo
                        </button>
                    @else
                        <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded cursor-not-allowed">
                            Próximo
                        </span>
                    @endif
                </div>
            </div>

            {{-- Tabela de Resultados --}}
            <x-portal::table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contratação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título / Área(s)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Itens</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Situação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">SEI</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Ações</th>
                    </tr>
                </x-slot:head>

                <x-slot:body>
                    @foreach($pcas as $pca)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                            {{-- UASG e Número --}}
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-bold whitespace-nowrap">
                                {{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }}
                            </td>

                            {{-- Título e Badges de Áreas --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $pca->tituloContratacao }}
                                </div>

                                @php
                                    $areas = array_filter(array_map('trim', explode('|||', $pca->areas_requisitantes ?? '')));
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

                            {{-- Total de Itens (Agregado direto na query) --}}
                            <td class="px-4 py-3 text-sm text-center text-gray-700 dark:text-gray-300 font-semibold">
                                {{ $pca->total_itens }}
                            </td>

                            {{-- Situação --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $pca->situacaoContratacao }}
                            </td>

                            {{-- Processo SEI --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ empty($pca->processoSei) ? '-' : $pca->processoSei }}
                            </td>

                            {{-- BOTAO DE DETALHES --}}
                            <td class="px-4 py-3 text-right align-middle whitespace-nowrap">
                                <x-portal::button 
                                    href="{{ route('detalhes', $pca->codigoPca) }}" 
                                    size="xs" 
                                    variant="primary" 
                                    icon="fa-eye"
                                >
                                </x-portal::button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-portal::table>
        </x-portal::card>    
    @endif
</div>