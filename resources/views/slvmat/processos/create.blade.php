<?php

use Livewire\Component;
use App\Models\Pca;
use App\Models\User;

new class extends Component
{
// Campos do Formulário
    public string $origem = 'EEL';
    public string $processoSei = '';
    public string $demandas = '';
    public string $centroGerencial = '';
    public string $objeto = '';
    public string $requester = '';
    public string $requesterEmail = '';
    public ?float $valorPrevisto = null;
    public string $modalidadeRoteiro = 'Concorrência - Obras';
    public string $criticidade = 'Média';
    public string $prioridade = 'Média';
    public string $complexidade = 'Média';
    public string $responsavel = '';
    public string $orientacaoChefia = '';

    protected function rules(): array
    {
        return [
            'origem'            => 'required|in:EEL,PUSP-L',
            'processoSei'       => 'required|string|max:50',
            'demandas'          => 'nullable|string|max:255',
            'centroGerencial'   => 'nullable|string|max:100',
            'objeto'            => 'required|string|min:10|max:2000',
            'requester'         => 'nullable|string|max:255',
            'requesterEmail'    => 'nullable|email|max:255',
            'valorPrevisto'     => 'nullable|numeric|min:0',
            'modalidadeRoteiro' => 'required|string',
            'criticidade'       => 'required|in:Baixa,Média,Alta',
            'prioridade'        => 'required|in:Baixa,Média,Alta',
            'complexidade'      => 'required|in:Baixa,Média,Alta',
            'responsavel'       => 'required|string',
            'orientacaoChefia'  => 'nullable|string|max:1000',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'origem'            => 'Origem',
            'processoSei'       => 'Processo SEI',
            'demandas'          => 'Demanda(s)',
            'centroGerencial'   => 'Centro Gerencial',
            'objeto'            => 'Objeto',
            'requester'         => 'Requisitante / Demandante',
            'requesterEmail'    => 'E-mail do demandante',
            'valorPrevisto'     => 'Valor previsto',
            'modalidadeRoteiro' => 'Modalidade / Roteiro',
            'criticidade'       => 'Criticidade',
            'prioridade'        => 'Prioridade',
            'complexidade'      => 'Complexidade',
            'responsavel'       => 'Responsável',
            'orientacaoChefia'  => 'Orientação da chefia',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'min'      => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'email'    => 'Informe um endereço de e-mail válido.',
            'numeric'  => 'O campo :attribute deve conter um valor numérico válido.',
        ];
    }

    public function salvar()
    {
        $this->validate();

        // Extrai as demandas separadas por ponto e vírgula
        $demandasArray = array_filter(array_map('trim', explode(';', $this->demandas)));

        // Cadastra o processo
        $pca = Pca::create([
            'codigoUASG'             => $this->origem === 'EEL' ? 160088 : 160089, // Exemplo de UASG por origem
            'numeroContratacao'      => date('YmdHis'),
            'processoSei'            => trim($this->processoSei),
            'areaRequisitante'       => $this->requester ?: $this->centroGerencial,
            'tituloContratacao'      => $this->objeto,
            'classificacaoContratacao' => $this->modalidadeRoteiro,
            'situacaoContratacao'    => 'Aguardando ciência',
            'valorTotal'             => $this->valorPrevisto ?? 0,
        ]);

        // Registra a distribuição no Histórico
        PcaHistorico::registrar(
            codigoUASG: $pca->codigoUASG,
            numeroContratacao: $pca->numeroContratacao,
            descricao: "Processo distribuído para {$this->responsavel}." . 
                       ($this->orientacaoChefia ? " Orientação da Chefia: {$this->orientacaoChefia}" : ""),
            userName: auth()->user()?->name ?? 'Chefia',
            situacaoAnterior: null,
            situacaoNova: 'Aguardando ciência'
        );

        session()->flash('success', "Processo SEI {$this->processoSei} distribuído com sucesso para {$this->responsavel}!");

        return redirect()->route('pca.show', $pca->id);
    }

    public function render()
    {
        $roteiros = [
            'Concorrência - Obras',
            'Pregão',
            'Pregão-enc.PGUSP',
            'ATA RP - cadesões+enc.PGUSP',
            'ATA RP',
            'Inexigibilidade-Nacional',
            'Inexigibilidade-Importação',
            'Inexigibilidade-Treinamentos',
            'DL com disputa',
            'DL sem disputa',
            'DL Concomitante',
        ];

        $compradores = [
            'Juliana',
            'Tatiana',
            'Luís',
            'Janaína',
            'Walkíria',
            'João Donizete',
            'Carlos Alberto',
            'Bruno',
        ];

        return view('slvmat.processos.index', [
            'roteiros'    => $roteiros,
            'compradores' => $compradores,
        ]);
    }
};
?>

@section('breadcrumbs')
    <a href="{{ Route::has('admin.dashboard') ? route('home') : '#' }}" class="hover:text-gray-700 hover:underline">SVLMAT</a>   
    <span>/</span>
    <span>Distribuir Processos</span>
@endsection

<div class="space-y-6">
{{-- CABEÇALHO DO FORMULÁRIO --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Distribuir novo processo
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            A distribuição cria o processo, aplica o roteiro e inicia a rastreabilidade.
        </p>
    </div>

    {{-- ALERTAS --}}
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- CARD DO FORMULÁRIO --}}
    <form wire:submit.prevent="salvar" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-5">
        
        {{-- LINHA 1: Origem | Processo SEI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Origem <span class="text-blue-600">*</span>
                </label>
                <select 
                    wire:model="origem"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('origem'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('origem')
                    ])
                >
                    <option value="EEL">EEL</option>
                    <option value="PUSP-L">PUSP-L</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Processo SEI <span class="text-blue-600">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model="processoSei" 
                    placeholder="154.00000000/2026-00"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('processoSei'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('processoSei')
                    ])
                />
            </div>
        </div>

        {{-- LINHA 2: Demanda(s) | Centro Gerencial --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Demanda(s)
                </label>
                <input 
                    type="text" 
                    wire:model="demandas" 
                    placeholder="107360/2026; 107421/2026"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('demandas'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('demandas')
                    ])
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Centro Gerencial
                </label>
                <input 
                    type="text" 
                    wire:model="centroGerencial" 
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('centroGerencial'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('centroGerencial')
                    ])
                />
            </div>
        </div>

        {{-- LINHA 3: Objeto --}}
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Objeto <span class="text-blue-600">*</span>
            </label>
            <textarea 
                wire:model="objeto" 
                rows="3"
                @class([
                    'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                    '!border-red-500 focus:!ring-red-500' => $errors->has('objeto'),
                    'border-gray-300 dark:border-gray-700' => !$errors->has('objeto')
                ])
            ></textarea>
        </div>

        {{-- LINHA 4: Requisitante / Demandante | E-mail do demandante --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Requisitante / Demandante
                </label>
                <input 
                    type="text" 
                    wire:model="requester" 
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('requester'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('requester')
                    ])
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    E-mail do demandante
                </label>
                <input 
                    type="email" 
                    wire:model="requesterEmail" 
                    placeholder="nome@usp.br"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('requesterEmail'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('requesterEmail')
                    ])
                />
            </div>
        </div>

        {{-- LINHA 5: Valor previsto | Modalidade / Roteiro --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Valor previsto
                </label>
                <input 
                    type="number" 
                    step="0.01"
                    wire:model="valorPrevisto" 
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('valorPrevisto'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('valorPrevisto')
                    ])
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Modalidade / Roteiro <span class="text-blue-600">*</span>
                </label>
                <select 
                    wire:model="modalidadeRoteiro"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('modalidadeRoteiro'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('modalidadeRoteiro')
                    ])
                >
                    @foreach($roteiros as $roteiro)
                        <option value="{{ $roteiro }}">{{ $roteiro }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- LINHA 6: Criticidade | Prioridade --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Criticidade
                </label>
                <select 
                    wire:model="criticidade"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('criticidade'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('criticidade')
                    ])
                >
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Prioridade
                </label>
                <select 
                    wire:model="prioridade"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('prioridade'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('prioridade')
                    ])
                >
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
        </div>

        {{-- LINHA 7: Complexidade | Responsável --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Complexidade
                </label>
                <select 
                    wire:model="complexidade"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('complexidade'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('complexidade')
                    ])
                >
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                    Responsável <span class="text-blue-600">*</span>
                </label>
                <select 
                    wire:model="responsavel"
                    @class([
                        'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                        '!border-red-500 focus:!ring-red-500' => $errors->has('responsavel'),
                        'border-gray-300 dark:border-gray-700' => !$errors->has('responsavel')
                    ])
                >
                    <option value="">Selecione um comprador...</option>
                    @foreach($compradores as $comprador)
                        <option value="{{ $comprador }}">{{ $comprador }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- LINHA 8: Orientação da chefia --}}
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Orientação da chefia
            </label>
            <textarea 
                wire:model="orientacaoChefia" 
                rows="3"
                placeholder="Observação opcional ao comprador"
                @class([
                    'w-full rounded-lg border text-sm p-2.5 bg-white dark:bg-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500',
                    '!border-red-500 focus:!ring-red-500' => $errors->has('orientacaoChefia'),
                    'border-gray-300 dark:border-gray-700' => !$errors->has('orientacaoChefia')
                ])
            ></textarea>
        </div>

        {{-- BOTÃO DE SUBMISSÃO ALINHADO À DIREITA --}}
        <div class="flex justify-end pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-5 py-2.5 rounded-lg shadow-sm transition duration-150 ease-in-out disabled:opacity-50 flex items-center gap-2"
            >
                <span wire:loading.remove wire:target="salvar">Distribuir processo</span>
                <span wire:loading wire:target="salvar">Distribuindo...</span>
            </button>
        </div>
    </form>
</div>