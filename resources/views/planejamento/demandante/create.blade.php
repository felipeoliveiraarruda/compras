<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pca;
use App\Models\Demandante;
use Uspdev\Replicado\Pessoa;

new class extends Component
{
    public ?Pca $selectedPca = null;
    public array $areasRequisitantes = [];
    public string $searchPca = '';
    public string $searchPessoa = '';
    public array $demandantesAdicionados = [];

    public function selectPca(int $codigoPca): void
    {
        $pca = Pca::find($codigoPca);

        if ($pca) {
            $this->selectedPca = $pca;

            // Busca todas as áreas distintas caso existam vários itens para a mesma contratação
            if ($pca->codigoUASG && $pca->numeroContratacao) {
                $this->areasRequisitantes = Pca::where('codigoUASG', $pca->codigoUASG)
                    ->where('numeroContratacao', $pca->numeroContratacao)
                    ->pluck('areaRequisitante')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();
            } else {
                $this->areasRequisitantes = array_filter([$pca->areaRequisitante]);
            }
        }

        $this->searchPca = '';
    }

    public function resetSelectedPca(): void
    {
        $this->selectedPca = null;
        $this->areasRequisitantes = [];
    }

    public function addPessoa(string $codpes, string $nompes): void
    {
        $jaAdicionado = collect($this->demandantesAdicionados)->contains('codpes', $codpes);

        if (!$jaAdicionado) {
            $this->demandantesAdicionados[] = [
                'codpes' => $codpes,
                'nompes' => $nompes,
            ];
        }

        $this->searchPessoa = '';
    }

    public function removeDemandante(int $index): void
    {
        unset($this->demandantesAdicionados[$index]);
        $this->demandantesAdicionados = array_values($this->demandantesAdicionados);
    }

    public function save()
    {
        $this->validate([
            'selectedPca' => 'required',
            'demandantesAdicionados' => 'required|array|min:1',
        ], [
            'selectedPca.required' => 'Por favor, selecione uma contratação.',
            'demandantesAdicionados.required' => 'Adicione pelo menos um demandante.',
            'demandantesAdicionados.min' => 'Adicione pelo menos um demandante.',
        ]);

        $usuarioLogado = Auth::user()->codpes ?? Auth::id();

        DB::transaction(function () use ($usuarioLogado) {
            foreach ($this->demandantesAdicionados as $pessoa) {
                Demandante::firstOrCreate(
                    [
                        'codigoPca' => $this->selectedPca->codigoPca,
                        'codigoPessoa' => $pessoa['codpes'],
                    ],
                    [
                        'codigoPessoaCriacao' => $usuarioLogado,
                        'codigoPessoaAlteracao' => $usuarioLogado,
                    ]
                );
            }
        });

        session()->flash('success', 'Demandante(s) cadastrado(s) com sucesso!');

        return redirect()->route('demandantes');
    }

    public function render()
    {
        // Busca de PCAs agregando as áreas requisitantes
        $pcasEncontradas = [];
        if (strlen(trim($this->searchPca)) >= 2) {
            $term = '%' . trim($this->searchPca) . '%';
            $pcasEncontradas = Pca::query()
                ->select('pca.*')
                ->selectRaw("GROUP_CONCAT(DISTINCT pca.areaRequisitante SEPARATOR '|||') as areas_requisitantes")
                ->where(function ($q) use ($term) {
                    $q->where('pca.numeroArtefato', 'like', $term)
                      ->orWhere('pca.anoArtefato', 'like', $term)
                      ->orWhere('pca.numeroContratacao', 'like', $term)
                      ->orWhere('pca.tituloContratacao', 'like', $term)
                      ->orWhere('pca.areaRequisitante', 'like', $term);
                })
                ->groupBy('pca.codigoUASG', 'pca.numeroContratacao')
                ->limit(10)
                ->get();
        }

        // Busca de Pessoas no Replicado
        $pessoasEncontradas = [];
        
        if (strlen(trim($this->searchPessoa)) >= 3) 
        {            
            $busca = trim($this->searchPessoa);
            
            try 
            {
                if (is_numeric($busca)) 
                {
                    $pessoa = Pessoa::dump($busca);

                    $pessoasEncontradas[] = 
                    [
                        'codpes' => $pessoa['codpes'],
                        'nompes' => $pessoa['nompesttd'],
                    ];
                } 
                else 
                {
                    $resultados = Pessoa::procurarPorNome($busca);

                    // limitando a resposta em 50 elementos
                    $resultados = array_slice($resultados, 0, 50);

                    $resultados = collect($resultados)->unique()->sortBy('nompesttd');
                    
                    foreach ($resultados as $res) 
                    {
                        $pessoasEncontradas[] = [
                            'codpes' => $res['codpes'],
                            'nompes' => $res['nompesttd'] ?? 'Nome não encontrado',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                $pessoasEncontradas = [];
            }
        }

        return view('planejamento.demandante.create', [
            'pcasEncontradas' => $pcasEncontradas,
            'pessoasEncontradas' => $pessoasEncontradas,
        ]);
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('admin') ? route('admin') : '#' }}" class="hover:text-gray-700 hover:underline">Dashboard</a>
    <span>/</span>
    <a href="{{ route('demandantes') }}" class="hover:text-gray-700 hover:underline">Demandantes</a>
    <span>/</span>
    <span>Novo</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header title="Cadastramento de Demandante(s)" subtitle="Selecione a contratação e adicione os demandantes vinculados." />

    <form wire:submit.prevent="save" class="space-y-6">

        {{-- SELEÇÃO DE PCA --}}
        <x-portal::card title="1. Selecionar Contratação">
            @if(!$selectedPca)
                <div class="relative">
                    <x-portal::input
                        label="Buscar Contratação (Número, Artefato, Título ou Área)"
                        wire:model.live.debounce.300ms="searchPca"
                        placeholder="Digite para buscar..."
                        wrapperClass="mb-0"
                    />

                    @if(strlen(trim($searchPca)) >= 2)
                        <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-y-auto">
                            @forelse($pcasEncontradas as $pca)
                                @php
                                    $areas = array_filter(array_map('trim', explode('|||', $pca->areas_requisitantes ?? $pca->areaRequisitante ?? '')));
                                @endphp
                                <div 
                                    wire:click="selectPca({{ $pca->codigoPca }})"
                                    class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700/50 cursor-pointer border-b last:border-b-0 border-gray-100 dark:border-gray-700"
                                >
                                    <div class="font-semibold text-sm text-gray-800 dark:text-gray-200">
                                        {{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }} — {{ $pca->tituloContratacao }}
                                    </div>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($areas as $ar)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ $ar }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-sm text-gray-500">Nenhuma contratação encontrada.</div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @else
                {{-- PCA Selecionada --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Contratação Selecionada</span>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 mt-0.5">
                            {{ $selectedPca->codigoUASG }}-{{ $selectedPca->numeroContratacao }} — {{ $selectedPca->tituloContratacao }}
                        </h4>
                        
                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Área(s):</span>
                            @forelse($areasRequisitantes as $area)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                    {{ $area }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-500 italic">Não informada</span>
                            @endforelse
                        </div>
                    </div>
                    <x-portal::button type="button" variant="secondary" size="xs" wire:click="resetSelectedPca">
                        Alterar
                    </x-portal::button>
                </div>
            @endif

            @error('selectedPca')
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
            @enderror
        </x-portal::card>

        {{-- SELEÇÃO DE DEMANDANTES --}}
        <x-portal::card title="2. Adicionar Demandantes">
            <div class="relative mb-4">
                <x-portal::input
                    label="Buscar Pessoa (Nº USP ou Nome)"
                    wire:model.live.debounce.300ms="searchPessoa"
                    placeholder="Digite o Nº USP ou nome..."
                    wrapperClass="mb-0"
                />

                @if(strlen(trim($searchPessoa)) >= 3)
                    <div class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        @forelse($pessoasEncontradas as $pessoa)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-100 dark:hover:bg-gray-700/50 border-b last:border-b-0 border-gray-100 dark:border-gray-700">
                                <div>
                                    <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">{{ $pessoa['nompes'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(Nº USP: {{ $pessoa['codpes'] }})</span>
                                </div>
                                <x-portal::button
                                    type="button"
                                    size="xs"
                                    icon="fa-plus"
                                    wire:click="addPessoa('{{ $pessoa['codpes'] }}', '{{ addslashes($pessoa['nompes']) }}')"
                                >
                                    Adicionar
                                </x-portal::button>
                            </div>
                        @empty
                            <div class="p-3 text-sm text-gray-500">Nenhuma pessoa encontrada.</div>
                        @endforelse
                    </div>
                @endif
            </div>

            @error('demandantesAdicionados')
                <span class="text-red-500 text-sm mb-4 block">{{ $message }}</span>
            @enderror

            {{-- Tabela de Selecionados --}}
            @if(count($demandantesAdicionados) > 0)
                <x-portal::table>
                    <x-slot:head>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nº USP</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Ação</th>
                        </tr>
                    </x-slot:head>
                    <x-slot:body>
                        @foreach($demandantesAdicionados as $index => $item)
                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $item['codpes'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200 font-medium">{{ $item['nompes'] }}</td>
                                <td class="px-4 py-2 text-right">
                                    <x-portal::button
                                        type="button"
                                        variant="danger"
                                        size="xs"
                                        icon="fa-trash"
                                        wire:click="removeDemandante({{ $index }})"
                                    >
                                        Remover
                                    </x-portal::button>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot:body>
                </x-portal::table>
            @endif
        </x-portal::card>

        <div class="flex justify-end space-x-3">
            <x-portal::button :href="route('demandantes')" variant="secondary">
                Cancelar
            </x-portal::button>

            <x-portal::button type="submit" icon="fa-check">
                Salvar
            </x-portal::button>
        </div>
    </form>
</div>