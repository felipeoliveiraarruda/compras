<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pca;
use App\Models\Demandante;
use Uspdev\Replicado\Pessoa;

new class extends Component
{
    public Pca $pca;
    public array $areasRequisitantes = [];
    public string $searchPessoa = '';
    public array $demandantesAdicionados = [];

    public function mount(Pca $pca)
    {
        $this->pca = $pca;

        // Busca todas as áreas requisitantes vinculadas a esta contratação
        if ($this->pca->codigoUASG && $this->pca->numeroContratacao) {
            $this->areasRequisitantes = Pca::where('codigoUASG', $this->pca->codigoUASG)
                ->where('numeroContratacao', $this->pca->numeroContratacao)
                ->pluck('areaRequisitante')
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        } else {
            $this->areasRequisitantes = array_filter([$this->pca->areaRequisitante]);
        }

        // Carrega demandantes já cadastrados
        $demandantesAtuais = Demandante::where('codigoPca', $this->pca->codigoPca)->get();

        foreach ($demandantesAtuais as $dem) {
            try {
                $pessoa = Pessoa::dump($dem->codigoPessoa);
                $nome = $pessoa['nompes'] ?? 'Nome não encontrado';
            } catch (\Throwable $e) {
                $nome = 'Não encontrado no Replicado';
            }

            $this->demandantesAdicionados[] = [
                'id' => $dem->codigoDemandante,
                'codpes' => $dem->codigoPessoa,
                'nompes' => $nome,
            ];
        }
    }

    public function addPessoa(string $codpes, string $nompes): void
    {
        $jaAdicionado = collect($this->demandantesAdicionados)->contains('codpes', $codpes);

        if (!$jaAdicionado) {
            $this->demandantesAdicionados[] = [
                'id' => null,
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
            'demandantesAdicionados' => 'required|array|min:1',
        ], [
            'demandantesAdicionados.required' => 'Mantenha pelo menos um demandante na lista.',
            'demandantesAdicionados.min' => 'Mantenha pelo menos um demandante na lista.',
        ]);

        $usuarioLogado = Auth::user()->codpes ?? Auth::id();

        DB::transaction(function () use ($usuarioLogado) {
            $codpesAtuais = collect($this->demandantesAdicionados)->pluck('codpes')->toArray();

            // 1. Remove demandantes desvinculados
            Demandante::where('codigoPca', $this->pca->codigoPca)
                ->whereNotIn('codigoPessoa', $codpesAtuais)
                ->delete();

            // 2. Insere novos demandantes
            foreach ($this->demandantesAdicionados as $pessoa) {
                Demandante::firstOrCreate(
                    [
                        'codigoPca' => $this->pca->codigoPca,
                        'codigoPessoa' => $pessoa['codpes'],
                    ],
                    [
                        'codigoPessoaCriacao' => $usuarioLogado,
                        'codigoPessoaAlteracao' => $usuarioLogado,
                    ]
                );
            }
        });

        session()->flash('success', 'Demandantes atualizados com sucesso!');

        return redirect()->route('demandantes');
    }

    public function render()
    {
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

        return view('planejamento.demandante.edit', [
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
    <span>Editar Demandantes</span>
@endsection

<div class="space-y-6">
    <x-portal::page-header title="Editar Demandantes da Contratação" subtitle="Gerencie os demandantes vinculados a esta contratação." />

    <x-portal::flash-messages />

    <form wire:submit.prevent="save" class="space-y-6">

        {{-- INFORMAÇÕES DA CONTRATAÇÃO --}}
        <x-portal::card title="Contratação Selecionada">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Contratação</span>
                <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                    {{ $pca->codigoUASG }}-{{ $pca->numeroContratacao }} — {{ $pca->tituloContratacao }}
                </h4>
                
                {{-- Badges das Áreas Requisitantes --}}
                <div class="mt-2 flex flex-wrap items-center gap-1">
                    <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">Área(s) Requisitante(s):</span>
                    @forelse($areasRequisitantes as $area)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                            {{ $area }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-500 italic">Não informada</span>
                    @endforelse
                </div>
            </div>
        </x-portal::card>

        {{-- GERENCIAR DEMANDANTES --}}
        <x-portal::card title="Demandantes Cadastrados">
            <div class="relative mb-4">
                <x-portal::input
                    label="Adicionar Mais Pessoas (Nº USP ou Nome)"
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

            {{-- Tabela de Demandantes Atuais --}}
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