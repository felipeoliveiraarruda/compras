<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Pca;

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
        if (empty($this->search))
        {
            $pcas = Pca::where('statusContratacao', 'Aprovada')
                        ->groupBy('codigoUASG', 'numeroContratacao')
                        ->paginate(15);               
        }
        else
        {
            $pcas = Pca::query()
                ->where('statusContratacao', 'Aprovada')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $term = '%' . trim($this->search) . '%';
                        $q->where('numeroArtefato', 'like', $term)
                            ->orWhere('anoArtefato', 'like', $term)
                            ->orWhere('numeroContratacao', 'like', $term)
                            ->orWhere('tituloContratacao', 'like', $term)
                            ->orWhere('areaRequisitante', 'like', $term)
                            ->orWhere('situacaoContratacao', 'like', $term);
                    });
                })
                ->groupBy('codigoUASG', 'numeroContratacao')
                ->get();        
        }

        return view('index',
        [
            'pcas' => $pcas,
        ]);
    }
};
?>

<div class="space-y-6">
    {{-- Filtros de Pesquisa --}}
    <x-portal::card>
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
            <div class="md:col-span-3">
                <x-portal::input
                    label="Buscar"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Pesquise por nº do artefato, ano, nº contratação, título ou área..."
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
            @if(!$search)
                {{-- BARRA SUPERIOR: Paginação customizada --}}
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    
                    {{-- Texto à ESQUERDA --}}
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Exibindo 
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->firstItem() ?? 0 }}</span> 
                        a 
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->lastItem() ?? 0 }}</span> 
                        de 
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $pcas->total() }}</span> 
                        resultados
                    </div>

                    {{-- Botões à DIREITA --}}
                    <div class="flex items-center space-x-2">
                        {{-- Botão Anterior --}}
                        @if ($pcas->onFirstPage())
                            <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 rounded cursor-not-allowed">
                                Anterior
                            </span>
                        @else
                            <button type="button" wire:click="previousPage" class="px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                Anterior
                            </button>
                        @endif

                        {{-- Botão Próximo --}}
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
            @endif

            <x-portal::table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contratação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Itens</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Área</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Situação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">SEI</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Detalhes</th>
                    </tr>
                </x-slot:head>

                <x-slot:body>
                    @foreach($pcas as $pca)
                        @php
                            $itens = App\Models\Pca::where('codigoUASG', $pca->codigoUASG)->where('numeroContratacao', $pca->numeroContratacao)->groupBy('numeroDfd')->count();
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->tituloContratacao }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $itens }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->areaRequisitante }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->situacaoContratacao }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->processoSei == '' ? '-' :  $pca->processoSei }}</td>
                            <td class="px-4 py-3 text-right"></td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-portal::table>
        </x-portal::card>    
    @endif
</div>