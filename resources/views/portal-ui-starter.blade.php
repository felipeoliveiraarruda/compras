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
use App\Models\Demandante;

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
        $pcas = Pca::query()
                ->doesntHave('demandantes')
                ->where('statusContratacao', 'Aprovada')
                ->where('situacaoContratacao', 'Em fase preparatória')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $term = '%' . trim($this->search) . '%';
                            $q->where('numeroArtefato', 'like', $term)
                            ->orWhere('anoArtefato', 'like', $term)
                            ->orWhere('numeroContratacao', 'like', $term)
                            ->orWhere('tituloContratacao', 'like', $term)
                            ->orWhere('areaRequisitante', 'like', $term);
                    });
                })
                ->groupBy('codigoUASG', 'numeroContratacao')
                ->orderBy('numeroArtefato')
                ->paginate(15);        

        return view('planejamento.demandante.index',
        [
            'pcas' => $pcas,
        ]);
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('admin') ? route('admin') : '#' }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <span>Demandantes</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header
        title="Demandantes"
        subtitle="">

        <x-slot:actions>
            <x-portal::button :href="route('demandantes.create')" icon="fa-plus">Novo</x-portal::button>
        </x-slot:actions>

    </x-portal::page-header>

    <x-portal::flash-messages />

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
                Nenhum resultado encontrado para a busca "{{ $search }}".
            @else
                Nenhuma contratação cadastrada.
            @endif
        </x-portal::alert>    
    @else
        <x-portal::card padding="false">
            <div class="p-4">
                {{ $pcas->links() }}
            </div>

            <x-portal::table>
                <x-slot:head>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contratação</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Área</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Detalhes</th>
                    </tr>
                </x-slot:head>

                <x-slot:body>
                    @foreach($pcas as $pca)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->tituloContratacao }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $pca->areaRequisitante }}</td>                            
                            <td class="px-4 py-3 text-right"></td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-portal::table>
        </x-portal::card>    
    @endif
</div>