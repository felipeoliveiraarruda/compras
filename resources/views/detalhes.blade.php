<?php

use Livewire\Component;
use App\Models\Pca;

new class extends Component
{
    public Pca $pca;
    public string $processoSei = '';
    public bool $editandoSei = false;
    public string $uasg = '';

    public function mount(Pca $pca)
    {
        $this->pca = $pca;
        $this->processoSei = $this->pca->processoSei ?? '';
        $this->uasg = (string) $this->pca->codigoUASG;
    }

    public function salvarProcessoSei()
    {
        $this->validate([
            'processoSei' => 'required|string|max:50',
        ], [
            'processoSei.required' => 'Informe o número do processo SEI.',
        ]);

        $novoSei = trim($this->processoSei);

        // Atualiza todas as linhas referentes a esta mesma contratação
        Pca::where('codigoUASG', $this->pca->codigoUASG)
            ->where('numeroContratacao', $this->pca->numeroContratacao)
            ->update([
                'processoSei'         => $novoSei,
                'situacaoContratacao' => 'Em análise DVAdm',
            ]);

        // Atualiza a instância em memória
        $this->pca->processoSei = $novoSei;
        $this->pca->situacaoContratacao = 'Em análise DVAdm';
        $this->editandoSei = false;

        session()->flash('success', 'Processo SEI cadastrado com sucesso! Situação alterada para "Em análise DVAdm".');
    }

    public function render()
    {
        // Agrupa os DFDs únicos pertencentes a esta contratação
        $dfds = Pca::select('numeroDfd', 'areaRequisitante', 'classificacaoContratacao', 'codigoPca')
            ->where('codigoUASG', $this->pca->codigoUASG)
            ->where('numeroContratacao', $this->pca->numeroContratacao)
            ->groupBy('codigoUASG', 'numeroContratacao', 'numeroDfd')
            ->get();

        return view('detalhes', [
            'dfds' => $dfds,
            'uasg' => $this->uasg,
        ]);
    }
};
?>

<div class="space-y-6">
    {{-- BARRA SUPERIOR: BOTÃO VOLTAR E INDICADORES --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <x-portal::button href="{{ url('/') }}" variant="secondary" icon="fa-arrow-left" size="sm">
            Voltar
        </x-portal::button>

        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900">
                UASG {{ $pca->codigoUASG ?? $uasg }} - Nº {{ $pca->numeroContratacao }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                {{ $pca->situacaoContratacao }}
            </span>
        </div>
    </div>

    {{-- ALERTAS DE SUCESSO --}}
    @if (session()->has('success'))
        <x-portal::alert variant="success" dismissible="true">
            {{ session('success') }}
        </x-portal::alert>
    @endif

    {{-- CARD PRINCIPAL: TÍTULO DA CONTRATAÇÃO E CAMPO DO PROCESSO SEI --}}
    <x-portal::card>
        <div class="space-y-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100 leading-snug">
                    {{ $pca->tituloContratacao }}
                </h1>
            </div>

            {{-- BLOCO DO PROCESSO SEI --}}
            @auth
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Processo SEI</h3>

                        @if(isset($editandoSei) && $editandoSei)
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <x-portal::input 
                                    wire:model="processoSei" 
                                    placeholder="Ex: 23062.000000/2026-00" 
                                    wrapperClass="mb-0"
                                />
                                <x-portal::button wire:click="salvarProcessoSei" size="sm" icon="fa-check">
                                    Salvar e Avançar
                                </x-portal::button>
                                <x-portal::button wire:click="$set('editandoSei', false)" variant="secondary" size="sm">
                                    Cancelar
                                </x-portal::button>
                            </div>
                            @error('processoSei')
                                <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                            @enderror
                        @else
                            <div class="mt-1 flex items-center gap-3">
                                <span class="text-base font-mono font-bold text-gray-800 dark:text-gray-100">
                                    {{ empty($pca->processoSei) ? 'Não informado' : $pca->processoSei }}
                                </span>
                                <button type="button" wire:click="$set('editandoSei', true)" class="text-xs text-blue-600 dark:text-blue-400 font-medium hover:underline">
                                    <i class="fa-solid fa-pen mr-1"></i>
                                    {{ empty($pca->processoSei) ? 'Cadastrar SEI' : 'Editar SEI' }}
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- ALERTA QUANDO ESTIVER EM FASE PREPARATÓRIA SEM SEI --}}
                    @if($pca->situacaoContratacao === 'Em fase preparatória' && empty($pca->processoSei))
                        <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 p-3 rounded-md text-xs max-w-md">
                            <i class="fa-solid fa-triangle-exclamation mr-1 font-bold"></i>
                            <strong>Atenção:</strong> Cadastre o Processo SEI acima para alterar a situação da contratação para <strong>"Em análise DVAdm"</strong>.
                        </div>
                    @endif
                </div>
            </div>
            @endauth
        </div>
    </x-portal::card>

    {{-- ACCORDION DE DFDS (USANDO ALPINE.JS + PORTAL UI TABLE) --}}
    <div class="space-y-4">
        <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-folder-tree text-blue-600"></i>
            DFDs Vinculados (Total: {{ count($dfds) }})
        </h2>

        @foreach($dfds as $dfd)
            @php
                $itens = \App\Models\Pca::where('codigoUASG', $pca->codigoUASG)
                    ->where('numeroContratacao', $pca->numeroContratacao)
                    ->where('numeroDfd', $dfd->numeroDfd)
                    ->orderBy('codigoPca')
                    ->get();
            @endphp

            <div x-data="{ open: false }" class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800 shadow-sm">
                {{-- CABEÇALHO DO ACCORDION --}}
                <button 
                    type="button" 
                    @click="open = !open" 
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-700/50 flex items-center justify-between text-left transition-colors border-b border-gray-200 dark:border-gray-700"
                >
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-chevron-right text-xs text-gray-400 transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                        <span class="font-bold text-sm text-gray-800 dark:text-gray-200">
                            DFD: {{ $dfd->numeroDfd }} - {{ $dfd->areaRequisitante }} - {{ $dfd->classificacaoContratacao }}
                        </span>
                    </div>

                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium">
                        {{ $itens->count() }} {{ Str::plural('item', $itens->count()) }}
                    </span>
                </button>

                {{-- TABELA DE ITENS --}}
                <div x-show="open" x-collapse>
                    <x-portal::table>
                        <x-slot:head>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Item</th>
                                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Valor Unitário (R$)</th>
                                <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Quantidade</th>
                                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300">Valor Total (R$)</th>
                            </tr>
                        </x-slot:head>

                        <x-slot:body>
                            @forelse($itens as $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200 align-top">
                                        @if(empty($item->descricaoMaterial))
                                            {{ $item->nomeClasse }}
                                        @else
                                            <strong>{{ $item->nomePdm }}</strong> - {{ $item->descricaoMaterial }}
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-right text-xs font-mono text-gray-700 dark:text-gray-300 align-top whitespace-nowrap">
                                        R$ {{ number_format((float) str_replace(',', '.', $item->valorUnitario ?? 0), 2, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 align-top whitespace-nowrap">
                                        {{ $item->quantidadeTotal ?? $item->quantidadeItem ?? 1 }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-xs font-mono font-bold text-gray-900 dark:text-gray-100 align-top whitespace-nowrap">
                                        R$ {{ number_format((float) str_replace(',', '.', $item->valorTotal ?? 0), 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-xs text-gray-400 italic">
                                        Nenhum item cadastrado para este DFD.
                                    </td>
                                </tr>
                            @endforelse
                        </x-slot:body>
                    </x-portal::table>
                </div>
            </div>
        @endforeach
    </div>
</div>