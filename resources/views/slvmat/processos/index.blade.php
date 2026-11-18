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
    <x-portal::page-header title="Distribuir Processo" subtitle="A distribuição cria o processo, aplica o roteiro e inicia a rastreabilidade." />

    <x-portal::flash-messages />    
</div>