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

    protected string $paginationTheme = 'bootstrap';

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
                DB::raw('MIN(id) as codigoPca'),
                DB::raw('MAX(tituloContratacao) as tituloContratacao'),
                DB::raw('MAX(situacaoContratacao) as situacaoContratacao'),
                DB::raw('MAX(processoSei) as processoSei'),
                DB::raw('COUNT(DISTINCT numeroDfd) as total_itens'),
                DB::raw("GROUP_CONCAT(DISTINCT areaRequisitante SEPARATOR '|||') as areas_requisitantes")
            )
            ->with(['demandantes'])
            ->where('statusContratacao', 'Aprovada')
            ->whereNotNull('processoSei')
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
            ->orderBy('anoArtefato', 'DESC')
            ->paginate(15);

        return view('dvadm.index', [
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
            {{-- Tabela de Resultados --}}
            <x-portal::table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contratação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">SEI</th>
                    </tr>
                </x-slot:head>

                <x-slot:body>
                    @foreach($pcas as $pca)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                            {{-- UASG e Número --}}
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-bold whitespace-nowrap">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" class="mt-1 rounded border-gray-300 text-portal focus:ring-portal/30">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }}</p>
                                    </div>
                                </div>                                
                            </td>

                            {{-- Título e Badges de Áreas --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                <div class="font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $pca->tituloContratacao }}
                                </div>
                            </td>

                            {{-- Processo SEI --}}
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ empty($pca->processoSei) ? '-' : $pca->processoSei }}
                            </td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-portal::table>       
        </x-portal::card>    
    @endif
</div>